<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $query = Task::query();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $tasks = $query->paginate(10); // 10 tareas por página
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en progreso,completada', 
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        return $task ? response()->json($task, 200) : response()->json(['message' => 'Task not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en progreso,completada', 
        ]);
        $task->update($validated);
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return response()->json(['message' => 'Task deleted'], 204);
        }
        return response()->json(['message' => 'Task not found'], 404);
    }
}
