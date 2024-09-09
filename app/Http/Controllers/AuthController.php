<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\UserFormRequest;
use App\Http\Requests\LoginFormRequest;

class AuthController extends Controller
{
    protected $authservice;
    /**
     * AuthController constructor
     * 
     * @param AuthService $authservice
     */
    public function __construct(AuthService $authservice)
    {
        $this->authservice = $authservice;
    }

    /**
     * Register a new user
     * 
     * @param UserFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserFormRequest $request)
    {
        $result = $this->authservice->register($request->validated());
        return response()->json($result['data']);
    }

    /**
     * Log in existing user
     * 
     * @param LoginFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginFormRequest $request)
    {
        $result = $this->authservice->login($request->validated());
        if (!$result['success']) {
            return response()->json($result['message'], $result['status']);
        }
        return response()->json($result['data']);
    }
    /**
     * Log out the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $result = $this->authservice->logout();
        if (!$result['success']) {
            return response()->json($result['message'], $result['status']);
        }
        return response()->json($result['message']);
    }
}
