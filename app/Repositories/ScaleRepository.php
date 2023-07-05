<?php

namespace App\Repositories;

use App\Models\Scale;
use App\Repositories\Repository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ScaleRepository extends Repository
{
    protected $modelClass = Scale::class;

    /**
     * @param  Array  $filter
     * @return \Illuminate\Http\Response
     */
    public function getItems(array $filter)
    {
        $query = $this->newQuery();
        if (isset($filter['search'])) {
            $search = $filter['search'];
            // shortcut to search only by id
            if ($search[0] === '/' && ctype_digit(substr($search, 1))) {
                $result = $query->where('id', intval(substr($search, 1)))->first();
                if (!$result) {
                    throw new HttpException(404, 'Not found');
                }
                return $result;
            } else {
                $query->whereRaw("id || name ILIKE " . "'%{$search}%'");
            }
        }
        $query->orderBy('id');
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
        $scale = $this->getItem('id', $id);
        if (!$scale) {
            throw new HttpException(404, 'Not found');
        }
        return $this->destroy($scale);
    }
}
