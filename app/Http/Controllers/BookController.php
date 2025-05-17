<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\ShowRequest;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\DeleteRequest;
use App\Http\Requests\Book\FilterRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Requests\Book\CatalogRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function filter(FilterRequest $request): JsonResponse
    {
        return $this->bookService->filter();
    }

    public function catalog(CatalogRequest $request): AnonymousResourceCollection
    {
        return $this->bookService->catalog(
            $request->page,
            $request->size,
            $request->order,
            $request->sort,
            $request->f_category,
            $request->f_author,
            $request->f_title,
            $request->f_publication
        );
    }

    public function show(ShowRequest $request): JsonResponse
    {
        return $this->bookService->show($request);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        return $this->bookService->store($request);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        return $this->bookService->update($request);
    }

    public function destroy(DeleteRequest $request, string $code): JsonResponse
    {
        return $this->bookService->destroy($code);
    }

    public function download(FilterRequest $request)
    {
        return $this->bookService->download(
            $request->f_category,
            $request->f_author,
            $request->f_title,
            $request->f_publication
        );
    }
}
