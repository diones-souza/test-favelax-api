<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      tags={"Users"},
     *      description="Display a list of records.",
     *      path="/api/users",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="search",
     *          description="Search for name/scale/id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     */
    public function getItems(Request $request)
    {
        return $this->service->getItems($request->all());
    }

    /**
     * @OA\Post(
     *      tags={"Users"},
     *      description="Store a record in the database.",
     *      path="/api/users",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="nickname", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="cpf", type="string"),
     *              @OA\Property(property="role_id", type="integer"),
     *              @OA\Property(property="scale_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email',
            'nickname' => 'required',
            'password' => 'required',
            'scale_id' => 'exists:scales,id',
            'role_id' => 'exists:roles,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "statusCode" => 400,
                "error" => $validator->errors()
            ], 400);
        }
        return $this->service->create($request->all());
    }

    /**
     * @OA\Put(
     *      tags={"Users"},
     *      description="Update the specified record in the database.",
     *      path="/api/users/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID to be updated",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="nickname", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="cpf", type="string"),
     *              @OA\Property(property="register", type="string"),
     *              @OA\Property(property="role_id", type="integer"),
     *              @OA\Property(property="scale_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(int $id, Request $request)
    {
        $data = ['id' => $id] + $request->all();
        $validator = Validator::make($data, [
            'id' => 'required|exists:users,id',
            'scale_id' => 'exists:scales,id',
            'role_id' => 'exists:roles,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "statusCode" => 400,
                "error" => $validator->errors()
            ], 400);
        }
        return $this->service->update($data);
    }

    /**
     * @OA\Delete(
     *      tags={"Users"},
     *      description="Delete a user based on the given ID",
     *      path="/api/users/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID to be deleted",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function delete(int $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "statusCode" => 400,
                "error" => $validator->errors()
            ], 400);
        }
        return $this->service->delete($id);
    }
}
