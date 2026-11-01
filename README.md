# Advanced Web Programming

Coursework from the **Advanced Web Programming** course (Master's study, Computer Engineering, FERIT Osijek, 2025).
Each folder is a separate lab exercise (LV = *laboratorijska vježba*), progressing from plain PHP to Laravel and Node.js applications.

| Folder | Stack | Summary |
| --- | --- | --- |
| [LV1](LV1) | PHP, MySQL | Web scraper that stores thesis listings in a database |
| [LV2](LV2) | PHP, MySQL, OpenSSL | Database backup, encrypted file storage and XML parsing |
| [LV3](LV3/projekti) | Laravel, Blade, Tailwind | Project management app with authentication and teams |
| [LV5](LV5/radovi) | Laravel, Blade, Bootstrap | Thesis topic application system with role-based access |
| [LV6](LV6/projects) | Node.js, Express, MongoDB | Project management CRUD app |
| [LV7](LV7/projects) | Node.js, Express, MongoDB, Passport | LV6 extended with user accounts, teams and archiving |

## PHP

### LV1 – Web scraper
- Scrapes thesis listings from the FERIT STUP website across multiple pages, following each link to fetch the full text
- Object-oriented design: a `DiplomskiRadovi` class implementing an `iRadovi` interface (`create`, `save`, `read`)
- Persists data to MySQL using prepared statements; credentials loaded from `.env`

### LV2 – Backup, encryption and XML
- **`db_backup.php`** – exports every table of a MySQL database to SQL `INSERT` statements and compresses them with gzip
- **`upload_encrypt.php` / `decrypt_display.php`** – encrypts uploaded files with AES-256-CBC (random IV) and authenticates them with an HMAC (encrypt-then-MAC), then verifies and decrypts them for download
- **`xml_parse.php`** – parses an XML dataset of people and renders it as a grid of profile cards

## Laravel

### LV3 – Project management app
- Full authentication (registration, login, email verification, password reset, profile management) built on Laravel Breeze
- Users create projects with a name, description, price, start and end date, and assign other users as team members
- Many-to-many relationship between users and projects via a pivot table (`project_user`), managed with Eloquent `belongsToMany` and `sync()`
- Separate views for projects a user leads and projects they are a member of
- Authorization: only the project leader can edit project details; team members can update completed tasks
- Request validation on all forms

### LV5 – Thesis topic application system
- Three roles – **admin**, **teacher** and **student** – enforced through a custom `RoleMiddleware`
- Admins assign roles to registered users
- Teachers publish thesis topics (title in Croatian and English, description, study level) and review applications
- Students apply for up to five open topics, each with a unique priority (1–5)
- Teachers can accept only students who ranked the topic as their first priority; accepting a student closes the topic and removes the remaining applications
- Localization: the interface switches between English and Croatian via a `SetLocale` middleware and language files
- Database schema built up through incremental migrations

## Node.js

### LV6 – Project management (Express + MongoDB)
- CRUD for projects using Express, Mongoose and Jade templates
- Routes respond with HTML or JSON depending on the request (content negotiation)

### LV7 – Users, teams and archive
- Extends LV6 with user registration and login using Passport (local strategy), bcrypt password hashing and sessions
- Project leaders manage team members; users see projects they lead or belong to
- Project archiving with a dedicated archive view

## Running locally

**PHP (LV1, LV2)** – install dependencies with Composer in the repository root (`vlucas/phpdotenv`), create a `.env` file in the exercise folder with database credentials (and `FIRST_KEY` / `SECOND_KEY` for LV2), then run the scripts with PHP.

**Laravel (LV3, LV5)**
```bash
cd LV3/projekti        # or LV5/radovi
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

**Node.js (LV6, LV7)** – requires a running MongoDB instance.
```bash
cd LV7/projects        # or LV6/projects
npm install
npm start
```
