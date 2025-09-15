<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // Get all tasks
    public function index(): JsonResponse
    {
        try {
            $tasks = Task::with('keywords')->get();
            return response()->json($tasks);
        } catch (Exception $error) {
            return response()->json([
                'error' => 'Failed to fetch tasks',
                'details' => $error->getMessage()
            ], 500);
        }
    }

    // Store one task
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = Task::create($request->validated());

            if ($request->has('keyword_ids'))
                $task->keywords()->sync($request->keyword_ids);

            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task->load('keywords')
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'error' => 'Failed to create task',
                'details' => $error->getMessage()
            ], 500);
        }
    }

    // Toggle task completion
    public function toggle($id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->is_done = !$task->is_done;
            $task->save();
            return response()->json([
                'message' => 'Task toggle successfully',
                'task' => $task->load('keywords')
            ]);
        } catch (ModelNotFoundException $error) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $error) {
            return response()->json([
                'error' => 'Failed to toggle task status',
                'details' => $error->getMessage()
            ], 500);
        }
    }

    // Update one task
    public function update(TaskRequest $request, $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($request->validated());

            if ($request->has('keyword_ids'))
                $task->keywords()->sync($request->keyword_ids);

            return response()->json([
                'message' => 'Task updated successfully',
                'task' => $task->load('keywords')
            ], 200);
        } catch (ModelNotFoundException $error) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $error) {
            return response()->json([
                'error' => 'The task could not be updated',
                'details' => $error->getMessage()
            ], 500);
        }
    }

    // Destroy one task
    public function destroy(string $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $error) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $error) {
            return response()->json([
                'error' => 'Failed to delete task',
                'details' => $error->getMessage()
            ], 500);
        }
    }
}
