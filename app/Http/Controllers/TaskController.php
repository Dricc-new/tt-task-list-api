<?php
namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::with('keywords')->get();
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        if ($request->has('keyword_ids')) {
            $task->keywords()->sync($request->keyword_ids);
        }

        return response()->json($task->load('keywords'), 201);
    }

    public function toggle($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->is_done = !$task->is_done;
        $task->save();

        return response()->json($task);
    }
}
