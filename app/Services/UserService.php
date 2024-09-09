<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserService
{

  /**
   * Retrieve all users.
   *
   * @return array The response containing the list of users and status code
   */
  public function AllUsers()
  {
    $users = User::with('roles')->get();
    return [
      'users' => $users,
      'status' => 201,
    ];
  }

  /**
   * Create a new user.
   *
   * @param array $data The data for creating a new user
   * @return array The response containing the created user and status code
   */
  public function createUser(array $data)
  {
    $user = new User();
    $user ->name = $data['name'];
    $user->email = $data['email'];
$user->password = Hash::make($data['password']);
$user->save();
$user->assignRole('user');
    return [
      'user' => $user,
      'status' => 201,
    ];
  }

  /**
   * Show a specific user.
   *
   * @param int $user
   * @return array The response containing the user and status code
   */
  public function showUser($user)
  {
    $user = User::findOrFail($user);

    if (!$user) {
      return [
        'success' => false,
        'message' => 'not found',
        'status' => 404,
      ];
    }

    return [
      'success' => true,
     'user' => $user->roles()->get(),
      'status' => 201,
    ];
  }

  /**
   * Update a specific user.
   *
   * @param array $data The data for updating the
   * @param int $user the user to update
   * @return array The response containing the updated user and status code
   */
  public function updateUser(array $data, $user)
  {

    $user = User::findOrFail($user);
    $user->update($data);
    if (!$user) {
      return [
        'success' => false,
        'message' => 'not found',
        'status' => 404,
      ];
    }

    return [
      'success' => true,
      'user' => $user,
      'status' => 201,
    ];
  }

  /**
   * Delete a specific user.
   *
   * @param int $user the user to delete
   * @return array The response indicating success or failure and status code
   */
  public function deleteUser($request, $user)
  {
    $user = User::withTrashed()->find($user);
    if ($request->has('Dlete_Permanently'))
      $user->forceDelete();
    else
      $user->delete();
    return [
      'success' => true,
      'message' => 'deleted',
      'status' => 200,
    ];
  }
  /**
   * Assign a role to a user.
   *
   * @param int $user The ID of the user to whom the role will be assigned.
   * @param string $role The name of the role to be assigned to the user.
   * @return array JSON response containing the result data.
   */
  public function AssignRoleToUser($user, $role)
  {
    $roletoassign = Role::findByName($role, 'api');
    $user = User::findOrFail($user);
    $user->removeRole($user->getRoleNames()->first());
    $user->assignRole($roletoassign);
    return [
      'success' => true,
      'data' => [
        'user' => $user,
        'role' => $role
      ],
      'status' => 200,
    ];
  }
  /**
   * Restore a soft-deleted user.
   *
   * @param int $user The ID of the user to be restored.
   * @return array JSON response containing the restored user or an error message.
   */
  public function RestoreDeletedUser($user)
  {
    $user = User::withTrashed()->find($user);
    if ($user && $user->trashed())
    // Restore the record
    {
      $user->restore();
      return [
        'success' => true,
        'user' => $user,
        'status' => 200,
      ];
    }
    return [
      'success' => false,
      'message' => 'not deleted',
      'status' => 404,
    ];
  }
}
