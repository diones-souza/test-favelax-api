<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private $repo;

    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     *  @param  array $data
     *  @return \Illuminate\Http\JsonResponse
     */
    public function login(array $data)
    {
        try {
            $user = null;
            if (!str_contains($data["email"], "@")) {
                $user = $this->repo->getItem('nickname', $data['email']);
                if ($user) {
                    $data["email"] = $user->email;
                }
            }
            $credentials =  $data;
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = User::find($user->id)->createToken('accessToken')->accessToken;

                return response()->json([
                    "statusCode" => 200,
                    "data" => [
                        'token' => $token,
                        'user' => $this->repo->getItem('email', $data['email'])
                    ]
                ], 200);
            } else {
                return response()->json([
                    "statusCode" => 401,
                    "error" => "Invalid username or password"
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 400,
                "error" => $th
            ], 400);
        }
    }
}
