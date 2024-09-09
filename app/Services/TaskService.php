<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskService
{

  /**
   * Retrieve all tasks based on user roles and filters.
   *
   * @param  $request The incoming request instance.
   * @return array The response containing the tasks and status code.
   */
  public function allTasks($request)
  {
    $user = JWTAuth::user();

    // Check if the user has the 'admin' role
    if ($user->hasRole('admin')) {
      // Include soft deleted tasks for admin
      $tasks = Task::withTrashed();
    } 
    if ($user->hasRole('manager')) {
      // Include soft deleted tasks for manager, filtered by user_id
      $tasks = Task::where('user_id', $user->id)
      ->orwhere('assigned_to',$user->id)->withTrashed();
    }

    // Apply priority filter if present
    if ($request->has('priority')) {
      $tasks = $tasks->ByPriority($request->priority);
    }

    // Apply status filter if present
    if ($request->has('status')) {
      $tasks = $tasks->ByStatus($request->status);
    }

    return [
      'tasks' => $tasks->get(),
      'status' => 201,
    ];
  }

  /**
   * Create a new task.
   *
   * @param array $data The data for creating the task.
   * @return array The response containing the created task and status code.
   */
  public function createTask(array $data)
  {
    $user = JWTAuth::user();
    $task = Task::create([
      'title' => $data['title'],
      'description' => $data['description'],
      'priority' => $data['priority'],
      'status' => 'in_progress',
      'assigned_to' => $data['assigned_to'],
      'user_id' => $user->id,
    ]);

    return [
      'task' => $task,
      'status' => 201,
    ];
  }

  /**
   * Show a specific task.
   *
   * @param int $task The ID of the task to show.
   * @return array The response containing the task and status code.
   */
  public function showTask($task)
  {
    $user = JWTAuth::user();
    if ($user->hasRole('admin')) 
     $task = Task::find($task);
     
     if ($user->hasRole('manager')) 
     $task = Task::where('task_id', $task)
     ->where(function($query) use ($user) {
         $query->where('user_id', $user->id)
               ->orWhere('assigned_to', $user->id);
     })
     ->first();

    return [
      'success' => true,
      'task' => $task,
      'status' => 201,
    ];
  
    if (!$task) {
      return [
        'success' => false,
        'message' => 'not found',
        'status' => 404,
      ];
    }
  }

  /**
   * Update a specific task.
   *
   * @param array $data The data for updating the task.
   * @param int $task The ID of the task to update.
   * @return array The response containing the updated task and status code.
   */
  public function UpdateTask(array $data, $task)
  {
    $user = JWTAuth::user();

    if ($user->hasRole('admin')) 
    $task = Task::findOrFail($task);

     if ($user->hasRole('manager')) 
     $task = Task::where('task_id', $task)
     ->where(function($query) use ($user) {
         $query->where('user_id', $user->id)
               ->orWhere('assigned_to', $user->id);
     })
     ->first();
     
     $task->update($data);

    if (!$task) {
      return [
        'success' => false,
        'message' => 'not found',
        'status' => 404,
      ];
    }

    return [
      'success' => true,
      'task' => $task,
      'status' => 201,
    ];
  }

  /**
   * Delete a specific task.
   *
   * @param int $task The ID of the task to delete.
   * @return array The response indicating success or failure and status code.
   */
  public function DeleteTask(Request $request,$task)
  {
    $user = JWTAuth::user();
    if ($user->hasRole('admin')) {
      $task = Task::withTrashed()->find($task);
    } elseif ($user->hasRole('manager')) {
      $task = Task::withTrashed()->where('task_id', $task)
     ->where(function($query) use ($user) {
         $query->where('user_id', $user->id)
               ->orWhere('assigned_to', $user->id);
     })
     ->first();
    }

    if ($request->has('Dlete_Permanently'))
    $task->forceDelete();
  else
    $task->delete();
      return [
        'success' => true,
        'message' => 'deleted',
        'status' => 200,
      ];
    
  }

  /**
   * Update the status of a specific task.
   *
   * @param array $data The data for updating the task status.
   * @param int $task The ID of the task to update.
   * @return array The response containing the updated task and status code.
   */
  public function updateTaskStatus(array $data, $task)
  {
   $user = JWTAuth::user();
  $task = Task::findOrFail($task)->where('assigned_to', $user->id)->first();
  
   if ( $task->update($data)) {
     
      return [
        'success' => true,
        'task' => $task,
        'status' => 201,
      ];
    }
    return [
      'success' => false,
      'message' => 'not found',
      'status' => 404,
    ];
  }

    


  /**
   * Assign a task to a user.
   *
   * @param Request $request The incoming request instance.
   * @param int $id The ID of the task to assign.
   * @return array The response indicating success or failure and status code.
   */
  public function assignTask(Request $request, $id)
  {
    $request->validate([
      'user_id' => 'required|exists:users,id',
    ]);
   $user = JWTAuth::user();
    $task = Task::find($id);
    if($user->id == $task->user_id)
   {   $task->assigned_to = $request->user_id;
      if($task->save())
      return [
        'message' => 'Task assigned successfully',
        'task' => $task,
        'status' => 200,
      ];
   }

    return [
      'message' => 'Task assignment failed',
      'status' => 400,
    ];
  }


  /**
   * Restore a soft-deleted task.
   *
   * @param int $task The ID of the task to restore.
   * @return array The response indicating success or failure and status code.
   */
  public function restoreDeletedTask($task)
  {
    $user = JWTAuth::user();
    if ($user->hasRole('admin')) 
 // Find the task including soft-deleted ones
 $task = Task::withTrashed()->find($task);

     if ($user->hasRole('manager')) 
     $task = Task::withTrashed()->where('task_id', $task)
     ->where(function($query) use ($user) {
         $query->where('user_id', $user->id)
               ->orWhere('assigned_to', $user->id);
     })
     ->first();
   

    // Check if the task exists and is soft-deleted
    if ($task && $task->trashed()) {
      // Restore the task
      $task->restore();
      return [
        'success' => true,
        'task' => $task,
        'status' => 200,
      ];
    }

    // Return failure response if the task is not found or not deleted
    return [
      'success' => false,
      'message' => 'not deleted',
      'status' => 404,
    ];
  }
}
