<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>

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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>My Tasks</h1>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add New Task</a>
        </div>

        @if ($tasks->isEmpty())
            <p class="text-center text-muted">No tasks created yet.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Task Name</th>
                        <th>Task Name (English)</th>
                        <th>Description</th>
                        <th>Study Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <!-- Link to Applications Page -->
                            <td><a href="{{ route('tasks.applications', $task->id) }}" class="text-decoration-none">{{ $task->naziv_rada }}</a></td>
                            <td>{{ $task->naziv_rada_engleski }}</td>
                            <td>{{ $task->zadatak_rada }}</td>
                            <td>{{ ucfirst($task->tip_studija) }}</td>
                            <td>{{ ucfirst($task->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
