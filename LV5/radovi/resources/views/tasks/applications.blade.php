<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications for {{ $task->naziv_rada }}</title>

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

        <h1 class="text-center my-4">Applications for {{ $task->naziv_rada }}</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($task->status === 'closed')
            <div class="alert alert-danger text-center">
                This task is closed. A student has already been accepted.
            </div>
        @endif

        @if ($applications->isEmpty())
            <p class="text-center text-muted">No students have applied for this task yet.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td>{{ $application->student->name }}</td>
                            <td>{{ $application->student->email }}</td>
                            <td>{{ $application->priority }}</td>

                            <!-- Accept Button Only for Priority 1 -->
                            @if ($application->priority == 1 && $task->status === 'open')
                                <td>
                                    <form method="POST" action="{{ route('applications.accept', ['applicationId' => $application->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Accept</button>
                                    </form>
                                </td>
                            @else
                                <!-- No Action for Other Priorities or Closed Task -->
                                <td>{{ $task->status === 'closed' ? 'Task Closed' : 'Cannot accept this priority.' }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Accepted Student Details -->
            @if ($task->status === 'closed' && $task->acceptedStudent)
                <div class="mt-4 p-3 bg-success text-white rounded">
                    <h2>Accepted Student:</h2>
                    <p><strong>Name:</strong> {{ $task->acceptedStudent->name }}</p>
                    <p><strong>Email:</strong> {{ $task->acceptedStudent->email }}</p>
                </div>
            @endif
        @endif
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
