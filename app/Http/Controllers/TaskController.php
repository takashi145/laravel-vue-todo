<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{

    public function task_list(Request $request, Category $category)
    {
        $tasks = $category->tasks()
            ->searchDeadline($request->deadline)
            ->searchStatus($request->completed)
            ->searchKeyword($request->keyword)
            ->betweenDeadline($request->deadline_start, $request->deadline_end)
            ->orderBy('deadline', 'asc')
            ->get();

        return TaskResource::collection($tasks);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TaskResource
     */
    public function index(Request $request)
    {
        $tasks = Task::searchDeadline($request->deadline)
        ->searchStatus($request->completed)
        ->searchKeyword($request->keyword)
        ->betweenDeadline($request->deadline_start, $request->deadline_end)
        ->orderBy('deadline', 'asc')
        ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->only(['title', 'description', 'deadline']));
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\TaskResource
     */
    public function show($id)
    {
        return new TaskResource(Task::findOrFail($id));
    }

    /**
     * Updates the status of the specified resource.
     * 
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->save();

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return response()->noContent();
    }
}
