<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

class TaskController extends Controller
{
  protected $taskservice;

  /**
   * TaskController constructor.
   *
   * @param \App\Services\TaskService $taskService
   */
  public function __construct(TaskService $taskService)
  {
    $this->taskservice = $taskService;
  }

  /**
   * Display a listing of the tasks.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    $result = $this->taskservice->AllTasks($request);
    return response()->json($result['tasks'], $result['status']);
  }

  /**
   * Store a newly created task in storage.
   *
   * @param \App\Http\Requests\TaskRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(TaskRequest $request)
  {
    $result = $this->taskservice->createTask($request->validated());
    return response()->json($result['task'], $result['status']);
  }

  /**
   * Display the specified task.
   *
   * @param int $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($task)
  {
    $result = $this->taskservice->ShowTask($task);
    if ($result['success']) {
      return response()->json($result['task'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }

  /**
   * Update the specified task in storage.
   *
   * @param \App\Http\Requests\TaskRequest $request
   * @param int $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(TaskRequest $request, $task)
  {
    $result = $this->taskservice->UpdateTask($request->validated(), $task);
    if ($result['success']) {
      return response()->json($result['task'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }

  /**
   * Update the status of the specified task.
   *
   * @param \App\Http\Requests\UpdateTaskStatusRequest $request
   * @param int $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateStatus(UpdateTaskStatusRequest $request, $task)
  {
    $result = $this->taskservice->updateTaskStatus($request->validated(), $task);
    if ($result['success']) {
      return response()->json($result['task'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }

  /**
   * Remove the specified task from storage.
   *
   * @param int $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Request $request,$task)
  {
    $result = $this->taskservice->DeleteTask($request,$task);
    return response()->json($result['message'], $result['status']);
  }

  /**
   * Assign a task to a user.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function AssignTask(Request $request, $id)
  {
    $result = $this->taskservice->AssignTask($request, $id);
    return response()->json($result['message'], $result['status']);
  }

  /**
   * Restore a soft-deleted task.
   *
   * @param int $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function RestoreDeletedTask($task)
  {
    $result = $this->taskservice->RestoreDeletedTask($task);
    if ($result['success']) {
      return response()->json($result['task'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }
}
