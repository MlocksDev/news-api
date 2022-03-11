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

namespace App\Services;

use App\Repositories\RepositoryInterface;

/**
 * Class AbstractService 
 * @package App\Services
 */
abstract class AbstractService implements ServiceInterface
{

    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->repository->create($data);
    }

    /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array
    {
        return $this->respository->findAll($limit, $orderBy);
    }

    /**
     * @param int $id
     * @return array
     */
    public function findOneBy(int $id): array
    {
        return $this->repository->findOneBy($id);
    }

    /**
     * @param string $param
     * @param array $data 
     * @return bool 
     */
    public function editBy(string $param, array $data): bool
    {
        return $this->repository->editBy($param, $data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param  array $orderBy
     * @return array
     */

    public function searchBy(
        string $string,
        array $searchFields,
        int $limit = 10,
        array $orderBy = []
    ): array {
        return $this->repository->searchBy($string, $searchFields, $limit, $orderBy);
    }
}
