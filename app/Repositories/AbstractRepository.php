<?php
/*
 * The MIT License
 *
 * Copyright 2022 Martha Ribeiro Locks.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractRepository
 * @package App\Repositories 
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * AbstractRepository Constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->model::create($data)->toArray();
    }

    /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array
    {
        $results = $this->model::query();

        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'limit' => $limit
            ])
            ->items();
    }

    /**
     * @param int $id
     * @return array
     */
    public function findOneBy(int $id): array
    {
        return $this->model::findOrFail($id)
            ->toArray();
    }

    /**
     * @param string $param
     * @param array $data 
     * @return bool 
     */
    public function editBy(string $param, array $data): bool
    {
        $result = $this->model::find($param)
            ->update($data);
        return $result ? true : false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model::destroy($id) ? true : false;
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param  array $orderBy
     * @return array
     */
    public function searchBy(string $string, array $searchFields, int $limit = 10, array $orderBy = []): array
    {
        $results = $this->model::where($searchFields[0], 'like', '%' . $string . '%');

        if (count($searchFields) > 1) {
            foreach ($searchFields as $field) {
                $results->orWhere($field, 'like', '%' . $string . '%');
            }
        }

        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'q' => $string,
                'limit' => $limit
            ])
            ->toArray();
    }
}
