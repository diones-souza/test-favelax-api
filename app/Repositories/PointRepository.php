<?php

namespace App\Repositories;

use App\Models\Point;
use App\Repositories\Repository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PointRepository extends Repository
{
    protected $modelClass = Point::class;

    /**
     * @param  Array  $filter
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $filter)
    {
        $query = $this->newQuery()
            ->selectRaw('points.*, u.name AS user_name')
            ->leftJoin('users AS u', 'points.user_id', '=', 'u.id');
        if (isset($filter['search'])) {
            $search = $filter['search'];
            // shortcut to search only by id
            if ($search[0] === '/' && ctype_digit(substr($search, 1))) {
                $result = $query->where('points.id', intval(substr($search, 1)))->first();
                if (!$result) {
                    throw new HttpException(404, 'Not found');
                }
                return $result;
            } else {
                $query->whereRaw("u.id || u.name || u.email || u.cpf || u.register || ILIKE " . "'%{$search}%'");
            }
        }
        $query->orderBy('points.id');
        if (isset($filter['page'])) {
            $result = $query->paginate($this->paginate);
            if ($result->isEmpty()) {
                throw new HttpException(404, 'Not found');
            }
            return $result;
        }
        $result = $query->get();
        if ($result->isEmpty()) {
            throw new HttpException(404, 'Not found');
        }
        return $result;
    }

    /**
     * @param  string  $key
     * @param  mixed $value
     * @return object|null
     */
    public function getItem(string $key, $value)
    {
        return $this->newQuery()
            ->where($key, $value)
            ->first();
    }

    /**
     * @param  Int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function delete(int $id)
    {
        $point = $this->getItem('id', $id);
        if (!$point) {
            throw new HttpException(404, 'Not found');
        }
        return $this->destroy($point);
    }
}
