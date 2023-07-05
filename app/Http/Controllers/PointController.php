<?php

namespace App\Http\Controllers;

use App\Services\PointService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PointController extends Controller
{
    private $service;

    public function __construct(PointService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      tags={"Points"},
     *      description="Display a list of records.",
     *      path="/api/points",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="search",
     *          description="Search for name/register/cpf/id/user_id",
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
     *      tags={"Points"},
     *      description="Store a record in the database.",
     *      path="/api/points",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="register", type="string", format="date-time"),
     *              @OA\Property(property="latitude", type="number", format="double", example=37.7749),
     *              @OA\Property(property="longitude", type="number", format="double", example=-122.4194),
     *              @OA\Property(property="photo", type="string", format="base64"),
     *              @OA\Property(property="user_id", type="integer"),
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
            'register' => 'required|date',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'photo' => 'base64image',
            'user_id' => 'required|exists:users,id'
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
     *      tags={"Points"},
     *      description="Update the specified record in the database.",
     *      path="/api/points/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Point ID to be updated",
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
     *              @OA\Property(property="register", type="string", format="date-time"),
     *              @OA\Property(property="latitude", type="number", format="double", example=37.7749),
     *              @OA\Property(property="longitude", type="number", format="double", example=-122.4194),
     *              @OA\Property(property="photo", type="string", format="base64"),
     *              @OA\Property(property="user_id", type="integer"),
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
            'id' => 'required|exists:points,id',
            'register' => 'date',
            'latitude' => 'numeric|min:-90|max:90',
            'longitude' => 'numeric|min:-180|max:180',
            'photo' => 'base64image',
            'user_id' => 'exists:users,id'
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
     *      tags={"Points"},
     *      description="Delete a user based on the given ID",
     *      path="/api/points/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Point ID to be deleted",
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
            'id' => 'required|exists:points,id',
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
