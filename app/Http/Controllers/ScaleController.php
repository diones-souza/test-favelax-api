<?php

namespace App\Http\Controllers;

use App\Services\ScaleService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ScaleController extends Controller
{
    private $service;

    public function __construct(ScaleService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      tags={"Scales"},
     *      description="Display a list of records.",
     *      path="/api/scales",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="search",
     *          description="Search for name",
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
     *      tags={"Scales"},
     *      description="Store a record in the database.",
     *      path="/api/scales",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string")
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
            'name' => 'required'
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
     *      tags={"Scales"},
     *      description="Update the specified record in the database.",
     *      path="/api/scales/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Scale ID to be updated",
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
     *              @OA\Property(property="name", type="string")
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
            'id' => 'required|exists:scales,id',
            'name' => 'required'
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
     *      tags={"Scales"},
     *      description="Delete a user based on the given ID",
     *      path="/api/scales/{id}",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Scale ID to be deleted",
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
            'id' => 'required|exists:scales,id',
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
