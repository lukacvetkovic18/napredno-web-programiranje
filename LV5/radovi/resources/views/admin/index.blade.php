<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="text-end">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        @if (session('success'))
            <p style="color: green;" class="alert alert-success">{{ session('success') }}</p>
        @endif

        <h1>Admin Panel</h1>

        @if ($users->isEmpty())
            <p>No users found.</p>
        @else
            <h2>User List</h2>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <!-- Role Change Form -->
                            @if ($user->id !== auth()->id())
                                <!-- Cannot change own role -->
                                <td>
                                    <form action="{{ route('admin.assign.role', $user->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <!-- Role Dropdown -->
                                        <select name="role" class="form-select d-inline w-auto">
                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="nastavnik" {{ $user->role === 'nastavnik' ? 'selected' : '' }}>Teacher</option>
                                        </select>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary">Change Role</button>
                                    </form>
                                </td>
                            @else
                                <!-- Message for own role -->
                                Nije moguće mijenjati svoju ulogu.
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Include Bootstrap JS -->
    <script src="{{ asset('js/main.js') }}"></script> 
</body>
</html>