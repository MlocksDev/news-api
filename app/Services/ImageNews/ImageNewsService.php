<?php

declare(strict_types=1);

namespace App\Services\ImageNews;

use App\Services\AbstractService;
use App\Repositories\ImageNews\ImageNewsRepository;

/**
 * Class ImageNewsService
 * @package App\Services\ImageNews
 */
class ImageNewsService extends AbstractService
{
    private ImageNewsRepository $repository;

    public function __construct(ImageNewsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $newsId
     * @return array
     */
    public function findByNews(int $newsId): array
    {
        return $this->repository->findByNews($newsId);
    }

    /**
     * @param int $newsId
     * @return bool 
     */
    public function deleteByNews(int $newsId): bool
    {
        return $this->repository->deleteByNews($newsId);
    }
}
