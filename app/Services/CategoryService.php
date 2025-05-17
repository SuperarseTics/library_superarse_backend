<?php

namespace App\Services;

use App\Models\Category;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Traits\CategoryValidationsTrait;
use App\Http\Requests\Category\ShowRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryService
{
    use CategoryValidationsTrait;

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get a paginated list of active categories.
     *
     * This method retrieves a list of categories that are active,
     * sorted based on the specified order and pagination parameters.
     *
     * @param int $page The current page number for pagination.
     * @param int $pageSize The number of categories per page.
     * @param string $pageOrder The column by which to order the results.
     * @param string $pageSort The sorting direction ('asc' or 'desc').
     *
     * @return AnonymousResourceCollection Returns a JSON response containing the paginated list of categories.
     */
    public function catalog(
        int $page,
        int $pageSize,
        string $pageOrder,
        string $pageSort
    ): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::active()
        ->orderBy($pageOrder, $pageSort)
        ->paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Get a single category by its ID.
     *
     * This method retrieves a category based on the provided ID.
     * If the category does not exist, it responds with an error message.
     *
     * @param ShowRequest $request The request containing the category code to be retrieved.
     *
     * @return JsonResponse Returns a JSON response containing the category data.
     */
    public function show(ShowRequest $request): JsonResponse
    {
        $category = $this->categoryRepository->findById($request->code);

        $this->existsCategory($category);

        return GeneralHelper::response(
            null,
            new CategoryResource($category),
            200
        );
    }

    /**
     * Create a new category.
     *
     * This method stores a new category using the provided request data.
     * It returns a success message and the newly created category resource.
     *
     * @param StoreRequest $request The request containing category data.
     *
     * @return JsonResponse Returns a JSON response with a success message and the created category data.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        // Catch all data of request in array
        $data = $request->toArray();

        // Create category with all properties
        $category = Category::create($data);

        return GeneralHelper::response(
            __('messages.category_created_success'),
            new CategoryResource($category),
            201
        );
    }

    /**
     * Update an existing category.
     *
     * This method updates a category based on the provided request data.
     * It checks for the existence of the category before updating.
     *
     * @param UpdateRequest $request The request containing updated category data.
     *
     * @return JsonResponse Returns a JSON response with a success message and the updated category data.
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        // Catch all data of request in array
        $data = $request->toArray();

        $category = $this->categoryRepository->findById($data['id']);

        $this->existsCategory($category);

        // Update category with all properties
        $category->update($data);

        return GeneralHelper::response(
            __('messages.category_updated_success'),
            new CategoryResource($category),
            201
        );
    }

    /**
     * Delete a category by its ID.
     *
     * This method removes a category from the database based on the provided ID.
     * It verifies the existence of the category before deletion.
     *
     * @param int $id The ID of the category to delete.
     *
     * @return JsonResponse Returns a JSON response with a success message.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryRepository->findById($id);

        $this->existsCategory($category);

        $category->delete();

        return GeneralHelper::response(
            __('messages.category_destroyed_success'),
            [],
            201
        );
    }
}
