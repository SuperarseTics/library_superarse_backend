<?php

namespace App\Traits;

use App\Models\Book;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;

trait BookValidationsTrait
{
    /**
     * Check if a book exists.
     *
     * This method verifies whether the provided book instance exists.
     * If the book is null, it returns a response indicating that the book was not found.
     *
     * @param Book|null $book The book instance to check for existence.
     *
     * @return JsonResponse|null Returns a JSON response indicating the result, or null if the book exists.
     */
    protected function existsBook(?Book $book): ?JsonResponse {
        if (!$book) {
            return GeneralHelper::response(
                __('messages.book_not_found'),
                [],
                404
            );
        }

        return null;
    }

    /**
     * Check if a book has stock.
     *
     * This method checks the stock level of the provided book.
     * If the book's stock is less than one, it returns a response indicating that the book is out of stock.
     *
     * @param Book $book The book instance to check for stock availability.
     *
     * @return JsonResponse|null Returns a JSON response indicating stock status, or null if the book has stock.
     */
    protected function checkStock(Book $book): ?JsonResponse {
        if ($book->stock < 1) {
            return GeneralHelper::response(
                __('messages.book_without_stock'),
                [],
                409
            );
        }

        return null;
    }
}
