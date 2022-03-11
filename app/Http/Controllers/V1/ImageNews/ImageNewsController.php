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

namespace App\Http\Controllers\V1\ImageNews;

use App\Http\Controllers\AbstractController;
use App\Services\ImageNews\ImageNewsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class ImageNewsController
 * @package App\Http\Controllers\V1\ImageNews
 */
class ImageNewsController extends AbstractController
{
    /**
     * @var array 
     */
    protected array $searchFields = [];

    /**
     * ImageNewsController constructor
     * @param ImageNewsService $service 
     */
    public function __construct(ImageNewsService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @param int $news
     * @return JsonResponse
     */
    public function findByNews(Request $request, int $news): JsonResponse
    {
        try {
            $result = $this->service->findByNews($news);
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * @param Request $request
     * @param int $news
     * @return JsonResponse
     */
    public function deleteByNews(Request $request, int $news): JsonResponse
    {
        try {
            $result['deletado'] = $this->service->deleteByNews($news);
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }

        return response()->json($response, $response['status_code']);
    }
}
