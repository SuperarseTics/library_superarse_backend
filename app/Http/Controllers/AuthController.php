<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticateRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        return $this->authService->authenticate($request);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request);
    }
}
