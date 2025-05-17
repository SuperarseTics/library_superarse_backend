<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $category;

    public function __construct(Category $category) {
        $this->category = $category;
    }

    /**
     * Find a category by its ID.
     *
     * This method retrieves a category from the database using its unique identifier.
     *
     * @param int $id The ID of the book to find.
     *
     * @return null|Category Returns the Category model if found, null otherwise.
     */
    public function findById(int $id): ?Category {
        return $this->category::find($id);
    }
}
