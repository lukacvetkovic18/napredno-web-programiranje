<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registration</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="mx-auto" style="max-width: 400px;">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" required autofocus class="form-control">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role" class="form-label">Select Role:</label>
                <select id="role" name="role" required class="form-select">
                    <option value="" disabled selected>Select Role</option>
                    <option value="student">Student</option>
                    <option value="nastavnik">Teacher</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="{{ asset('js/main.js') }}"></script> 
</body>
</html>
