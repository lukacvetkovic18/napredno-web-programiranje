<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\StudentTaskApplication;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    // NASTAVNIK

    public function myTasks()
    {
        // Fetch all tasks created by the authenticated teacher
        $tasks = Task::where('user_id', auth()->id())->get();

        return view('tasks.my', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naziv_rada' => 'required|string|max:255',
            'naziv_rada_engleski' => 'required|string|max:255',
            'zadatak_rada' => 'required|string',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        Task::create([
            'naziv_rada' => $request->naziv_rada,
            'naziv_rada_engleski' => $request->naziv_rada_engleski,
            'zadatak_rada' => $request->zadatak_rada,
            'tip_studija' => $request->tip_studija,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tasks.create')->with('success', __('messages.task_added'));
    }

    public function showApplications($taskId)
    {
        // Fetch the task to ensure it belongs to the authenticated teacher
        $task = Task::findOrFail($taskId);

        // Check if the authenticated user is the teacher of the task
        if ($task->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to view applications for this task.');
        }

        // Fetch students who applied for this task
        $applications = StudentTaskApplication::where('task_id', $taskId)->with('student')->get();

        return view('tasks.applications', compact('applications', 'task'));
    }

    public function acceptStudent(Request $request, $applicationId)
    {
        // Fetch the application
        $application = StudentTaskApplication::findOrFail($applicationId);

        // Ensure the authenticated user is the teacher of the task
        $task = $application->task;
        if ($task->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to accept students for this task.');
        }

        // Ensure the student is being accepted for priority 1
        if ($application->priority !== 1) {
            return redirect()->back()->withErrors(['error' => 'You can only accept students for priority 1 tasks.']);
        }

        // Update task with the accepted student ID and change status to 'closed'
        $task->update([
            'accepted_student_id' => $application->student_id,
            'status' => 'closed', // Change status to closed
        ]);

        // Optionally delete other applications for this task
        StudentTaskApplication::where('task_id', $task->id)->where('id', '!=', $applicationId)->delete();

        return redirect()->route('tasks.applications', ['taskId' => $task->id])->with('success', 'Student successfully accepted, and task is now closed.');
    }

    // STUDENT

    public function indexForStudent()
    {
        // Fetch only tasks with status 'open'
        $tasks = Task::where('status', 'open')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function apply(Request $request, $taskId)
    {
        // Validate that the task exists and is open for application
        $task = Task::findOrFail($taskId);

        if ($task->status !== 'open') {
            return redirect()->back()->withErrors(['error' => 'This task is not open for applications.']);
        }

        // Check if the student has already applied for this task
        $existingApplication = StudentTaskApplication::where([
            ['student_id', auth()->id()],
            ['task_id', $taskId],
        ])->first();

        if ($existingApplication) {
            return redirect()->back()->withErrors(['error' => 'You have already applied for this task.']);
        }

        // Check if the student has already applied for 5 tasks
        $applicationCount = StudentTaskApplication::where('student_id', auth()->id())->count();
        if ($applicationCount >= 5) {
            return redirect()->back()->withErrors(['error' => 'You can only apply for up to 5 tasks.']);
        }

        // Validate priority input
        $request->validate([
            'priority' => 'required|integer|min:1|max:5',
        ]);

        // Check if priority is unique among student's applications
        $existingPriority = StudentTaskApplication::where('student_id', auth()->id())
            ->where('priority', $request->priority)
            ->first();

        if ($existingPriority) {
            return redirect()->back()->withErrors(['error' => 'You have already applied with this priority.']);
        }

        // Create application record
        StudentTaskApplication::create([
            'student_id' => auth()->id(),
            'task_id' => $taskId,
            'priority' => $request->priority,
        ]);

        return redirect()->route('tasks.index')->with('success', 'You have successfully applied for the task.');
    }

}
