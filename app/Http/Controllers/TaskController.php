<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // 1. Create Task
    public function store(StoreTaskRequest $request)
    {
        // Check for duplicate title on same due_date
        $exists = Task::where('title', $request->title)
                      ->where('due_date', $request->due_date)
                      ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A task with this title already exists for the given due date.'
            ], 422);
        }

        $task = Task::create([
            'title'    => $request->title,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status'   => 'pending',
        ]);

        return response()->json($task, 201);
    }

    // 2. List Tasks
    public function index(Request $request)
    {
        $query = Task::query();

        // Optional status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort by priority (high → medium → low), then due_date ascending
        $tasks = $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                       ->orderBy('due_date', 'asc')
                       ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found.',
                'data'    => []
            ], 200);
        }

        return response()->json($tasks, 200);
    }

    // 3. Update Task Status
    public function updateStatus(UpdateTaskStatusRequest $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        $nextStatus = Task::$statusFlow[$task->status] ?? null;

        if ($nextStatus === null) {
            return response()->json([
                'message' => 'This task is already done and cannot be updated further.'
            ], 422);
        }

        if ($request->status !== $nextStatus) {
            return response()->json([
                'message' => "Invalid status transition. Expected next status: '{$nextStatus}'."
            ], 422);
        }

        $task->update(['status' => $nextStatus]);

        return response()->json($task, 200);
    }

    // 4. Delete Task
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        if ($task->status !== 'done') {
            return response()->json([
                'message' => 'Only completed (done) tasks can be deleted.'
            ], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }

    // 5. BONUS: Daily Report
    public function report(Request $request)
    {
        $date = $request->query('date');

        if (!$date || !strtotime($date)) {
            return response()->json(['message' => 'Please provide a valid date in YYYY-MM-DD format.'], 422);
        }

        $priorities = ['high', 'medium', 'low'];
        $statuses   = ['pending', 'in_progress', 'done'];
        $summary    = [];

        foreach ($priorities as $priority) {
            foreach ($statuses as $status) {
                $summary[$priority][$status] = Task::where('priority', $priority)
                    ->where('status', $status)
                    ->whereDate('due_date', $date)
                    ->count();
            }
        }

        return response()->json([
            'date'    => $date,
            'summary' => $summary,
        ], 200);
    }
}