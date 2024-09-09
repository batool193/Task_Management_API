<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthService
 * 
 * This service handles user authentication, including registration, login, and logout.
 */
class AuthService
{

    /**
     * Register a new user and generate a JWT token.
     *
     * @param array $validateddata The validated user data.
     * @return array The response containing the user and JWT token.
     */
    public static function register($validateddata)
    {    
            $user = new User();
                $user ->name = $validateddata['name'];
                $user->email = $validateddata['email'];
            $user->password = Hash::make($validateddata['password']);
            $user->save();
            $user->assignRole('user');
        
            $token = JWTAuth::fromUser($user);
            if($token)
            return [
                'success' => true,
                'data' => [
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer'
                    ]
                ]
            ];
            return [
                'success' => false,
                'message' => 'An error occurred during authentication.',
                'status' => 500,
            ];
    }

    /**
     * Authenticate a user and generate a JWT token.
     *
     * @param array $validateddata The validated user data.
     * @return array The response containing the user and JWT token or an error message.
     */

    public static function login($validatedData)
    {  if (!$token = JWTAuth::attempt($validatedData)) 
                return [
                    'success' => false,
                    'message' => 'Unauthorized',
                    'status' => 401,
                ];
            $user = JWTAuth::user();
            return [
                'success' => true,
                'data' => [
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer'
                    ]
                ]
            ];
    }

    /**
     * Logout the authenticated user.
     *
     * @return array The response indicating successful logout.
     */
    public static function logout()
    {
      if(JWTAuth::invalidate(JWTAuth::getToken()))
            return [
                'success' => true,
                'message' => 'Successfully logged out',
            ];
       
            return [
                'success' => false,
                'message' => 'Failed to logout, please try again',
                'status' => 500,
            ];
        }
    }

