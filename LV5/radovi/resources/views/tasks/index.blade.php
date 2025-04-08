<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tasks</title>

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

        <h1 class="text-center my-4">Available Tasks</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($tasks->isEmpty())
            <p class="text-center text-muted">No tasks available at the moment.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Task Name</th>
                        <th>Task Name (English)</th>
                        <th>Description</th>
                        <th>Study Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->naziv_rada }}</td>
                            <td>{{ $task->naziv_rada_engleski }}</td>
                            <td>{{ $task->zadatak_rada }}</td>
                            <td>{{ ucfirst($task->tip_studija) }}</td>
                            <td>
                                @if ($task->status === 'open')
                                    <form method="POST" action="{{ route('tasks.apply', ['taskId' => $task->id]) }}">
                                        @csrf
                                        <!-- Priority Field -->
                                        <label for="priority">Priority:</label>
                                        <select name="priority" id="priority" required class="form-select d-inline w-auto">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </form>
                                @else
                                    Closed
                                @endif
                            </td>
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