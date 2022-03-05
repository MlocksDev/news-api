<?php

declare(strict_types=1);

namespace App\Services\News;

use App\Services\AbstractService;
use App\Repositories\News\NewsRepository;

/**
 * Class NewsService
 * @package App\Services\News
 */
class NewsService extends AbstractService
{

    private NewsRepository $repository;

    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $authorId
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findByAuthor(int $authorId, int $limit = 10, array $orderBy = []): array
    {
        return $this->repository->findByAuthor($authorId, $limit, $orderBy);
    }

    /**
     * @param string $param
     * @return array
     */
    public function findBy(string $param): array
    {
        return $this->repository->findBy($param);
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
     * @param string $param
     * @return bool
     */
    public function deleteBy(string $param): bool
    {
        return $this->repository->deleteBy($param);
    }

    /**
     * @param int $authorId
     * @return bool
     */
    public function deleteByAuthor(int $authorId): bool
    {
        return $this->repository->deleteByAuthor($authorId);
    }
}
