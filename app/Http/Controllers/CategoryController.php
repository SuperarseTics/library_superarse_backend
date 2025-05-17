<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\ShowRequest;
use App\Http\Requests\Category\IndexRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\DeleteRequest;
use App\Http\Requests\Category\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function catalog(IndexRequest $request): AnonymousResourceCollection
    {
        return $this->categoryService->catalog(
            $request->page,
            $request->size,
            $request->order,
            $request->sort
        );
    }

    public function show(ShowRequest $request): JsonResponse
    {
        return $this->categoryService->show($request);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        return $this->categoryService->store($request);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        return $this->categoryService->update($request);
    }

    public function destroy(DeleteRequest $request, int $code): JsonResponse
    {
        return $this->categoryService->destroy($code);
    }
}
