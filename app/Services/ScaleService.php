<?php

namespace App\Services;

use App\Repositories\ScaleRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ScaleService
{
    private $repo;

    public function __construct(ScaleRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param  array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems(array $data)
    {
        try {

            return response()->json([
                "statusCode" => 200,
                "data" => $this->repo->getItems($data)
            ], 200);
        } catch (HttpException $e) {
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json([
                "statusCode" => 400,
                "error" => $th
            ], 400);
        }
    }

    /**
     * @param  array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $scale = $this->repo->create($data);
            DB::commit();
            return response()->json([
                "statusCode" => 201,
                "data" => $scale
            ], 201);
        } catch (HttpException $e) {
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function update(array $data)
    {
        DB::beginTransaction();
        try {

            $scale = $this->repo->findOne($data['id']);
            $scale = $this->repo->update($scale, $data);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $scale
            ], 200);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
            ], 400);
        }
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        DB::beginTransaction();
        try {

            $scale = $this->repo->delete($id);
            DB::commit();
            return response()->json([
                "statusCode" => 200,
                "data" => $scale
            ], 200);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                "statusCode" => $e->getStatusCode(),
                "error" => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "statusCode" => 400,
                "error" => $th->getMessage()
            ], 400);
        }
    }
}
