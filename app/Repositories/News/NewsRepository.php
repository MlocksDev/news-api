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

namespace App\Repositories\News;

use App\Repositories\AbstractRepository;

/**
 * Class NewsRepository
 * @package App\Repositories\News
 */
class NewsRepository extends AbstractRepository
{
    /**
     * @param int $authorId
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findByAuthor(int $authorId, int $limit = 10, array $orderBy = []): array
    {
        $results = $this->model::where('author_id', $authorId);

        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'limit' => $limit,
            ])
            ->toArray();
    }

    /**
     * @param string $param
     * @return array
     */
    public function findBy(string $param): array
    {
        $query = $this->model::query();

        if (is_numeric($param)) {
            $news = $query->findOrFail($param);
        } else {
            $news = $query->where('slug', $param)
                ->get();
        }

        return $news->toArray();
    }

    /**
     * @param string $param
     * @param array $data 
     * @return bool 
     */
    public function editBy(string $param, array $data): bool
    {
        if (is_numeric($param)) {
            $news = $this->model::find($param);
        } else {
            $news = $this->model::where('slug', $param);
        }

        return $news->update($data) ? true : false;
    }

    /**
     * @param string $param
     * @return bool
     */
    public function deleteBy(string $param): bool
    {
        if (is_numeric($param)) {
            $news = $this->model::destroy($param);
        } else {
            $news = $this->model::where('slug', $param)
                ->delete();
        }

        return $news ? true : false;
    }

    /**
     * @param int $authorId
     * @return bool
     */
    public function deleteByAuthor(int $authorId): bool
    {
        $news = $this->model::where('author_id', $authorId)
            ->delete();

        return $news ? true : false;
    }
}
