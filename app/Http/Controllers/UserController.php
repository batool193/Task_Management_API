<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserFormRequest;
use Illuminate\Http\Request;

/**
 * Class UserController
 * 
 * This controller handles the CRUD operations for users
 */
class UserController extends Controller
{
  protected $userservice;
  /**
   * UserController constructor
   * 
   * @param UserService $userService
   */
  public function __construct(UserService $userService)
  {
    $this->userservice = $userService;
  }
  /**
   * Display a listing of the users
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    $result = $this->userservice->AllUsers();
    return response()->json($result['users'], $result['status']);
  }

  /**
   * Store a newly created user in storage
   * 
   * @param UserFormRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(UserFormRequest $request)
  {
    $result = $this->userservice->CreateUser($request->validated());
    return response()->json($result['user'], $result['status']);
  }
  /**
   * Display the specified user
   * 
   * @param int $user
   * @return \Illuminate\Http\JsonResponse
   */

  public function show($user)
  {
    $result = $this->userservice->ShowUser($user);
    if ($result['success']) {
      return response()->json($result['user'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }
  /**
   * Update the specified user in storage
   * 
   * @param UserFormRequest $request
   * @param int $user
   * @return \Illuminate\Http\JsonResponse
   */

  public function update(UserFormRequest $request, $user)
  {
    $result = $this->userservice->UpdateUser($request->validated(), $user);
    if ($result['success']) {
      return response()->json($result['user'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }
  /**
   * Remove the specified user from storage
   * 
   * @param int $user
   * @return \Illuminate\Http\JsonResponse
   */

  public function destroy(Request $request, $user)
  {
    $result = $this->userservice->DeleteUser($request, $user);
    return response()->json($result['message'], $result['status']);
  }
  /**
   * Assign a role to a user.
   *
   * @param int $user The user to whom the role will be assigned.
   * @param string $role The role to be assigned to the user.
   * @return \Illuminate\Http\JsonResponse JSON response containing the result data.
   */
  public function AssignRoleToUser($user, $role)
  {
    $result = $this->userservice->AssignRoleToUser($user, $role);
    return response()->json($result['data'], 200);
  }
  /**
   * Restore a soft-deleted user.
   *
   * @param int $user The user to be restored.
   * @return \Illuminate\Http\JsonResponse JSON response containing the restored user or an error message.
   */
  public function RestoreDeletedUser($user)
  {
    $result = $this->userservice->RestoreDeletedUser($user);
    if ($result['success']) {
      return response()->json($result['user'], $result['status']);
    }

    return response()->json($result['message'], $result['status']);
  }
}
