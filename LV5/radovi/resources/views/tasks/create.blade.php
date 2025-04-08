<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.add_task') }}</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <!-- Language Switcher -->
        <nav class="mb-4 text-end">
            <a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="btn btn-link">English</a> |
            <a href="{{ route('lang.switch', ['locale' => 'hr']) }}" class="btn btn-link">Hrvatski</a>
        </nav>

        <h1 class="text-center">{{ __('messages.add_task') }}</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Task Form -->
        <form method="POST" action="{{ route('tasks.store') }}" class="mt-4">
            @csrf

            <!-- Task Name -->
            <div class="mb-3">
                <label for="naziv_rada" class="form-label">{{ __('messages.task_name') }}</label>
                <input type="text" id="naziv_rada" name="naziv_rada" required class="form-control">
            </div>

            <!-- Task Name (English) -->
            <div class="mb-3">
                <label for="naziv_rada_engleski" class="form-label">{{ __('messages.task_name_english') }}</label>
                <input type="text" id="naziv_rada_engleski" name="naziv_rada_engleski" required class="form-control">
            </div>

            <!-- Task Description -->
            <div class="mb-3">
                <label for="zadatak_rada" class="form-label">{{ __('messages.task_description') }}</label>
                <textarea id="zadatak_rada" name="zadatak_rada" required class="form-control"></textarea>
            </div>

            <!-- Study Type -->
            <div class="mb-3">
                <label for="tip_studija" class="form-label">{{ __('messages.study_type') }}</label>
                <select id="tip_studija" name="tip_studija" required class="form-select">
                    <option value="" disabled selected>Select Study Type</option>
                    <option value="stručni">Stručni</option>
                    <option value="preddiplomski">Preddiplomski</option>
                    <option value="diplomski">Diplomski</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success">{{ __('messages.submit') }}</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="{{ asset('js/main.js') }}"></script> 
</body>
</html>
