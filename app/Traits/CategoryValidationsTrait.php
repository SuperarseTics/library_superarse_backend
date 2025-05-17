<?php

namespace App\Traits;

use App\Models\Category;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;

trait CategoryValidationsTrait
{
    /**
     * Check if a category exists.
     *
     * This method verifies whether the provided category instance exists.
     * If the category is null, it returns a response indicating that the category was not found.
     *
     * @param Category|null $category The category instance to check for existence.
     *
     * @return JsonResponse|null Returns a JSON response indicating the result, or null if the category exists.
     */
    protected function existsCategory(?Category $category): ?JsonResponse {
        if (!$category) {
            return GeneralHelper::response(
                __('messages.category_not_found'),
                [],
                404
            );
        }

        return null;
    }
}
