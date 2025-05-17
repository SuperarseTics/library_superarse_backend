<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    protected $book;

    public function __construct(Book $book) {
        $this->book = $book;
    }

    /**
     * Find a book by its ID.
     *
     * This method retrieves a book from the database using its unique identifier.
     *
     * @param int $id The ID of the book to find.
     *
     * @return null|Book Returns the Book model if found, null otherwise.
     */
    public function findById(int $id): ?Book {
        return $this->book::find($id);
    }

    /**
     * Find all active books.
     *
     * This method retrieves all book from the database.
     *
     * @param string|null $fCategory (Optional) The category filter to apply.
     * @param string|null $fAuthor (Optional) The author filter to apply.
     * @param string|null $fTitle (Optional) The title filter to apply.
     * @param int|null $fPublication (Optional) The publication year filter to apply.
     *
     * @return Collection.
     */
    public function getAllBooks(
        ?string $fCategory,
        ?string $fAuthor,
        ?string $fTitle,
        ?int $fPublication
    ) {
        return $this->book::filterByCategory($fCategory)
        ->filterByAuthor($fAuthor)
        ->filterByTitle($fTitle)
        ->filterByPublication($fPublication)
        ->with(['category'])
        ->get();
    }

    /**
     * Find a book by its code.
     *
     * This method retrieves a book from the database using its unique code.
     *
     * @param string $code The code of the book to find.
     *
     * @return null|Book Returns the Book model if found, null otherwise.
     */
    public function findByCode(string $code): ?Book {
        return $this->book::where('code', $code)->first();
    }

    /**
     * List all authors of active books.
     *
     * This method retrieves a distinct list of authors from the active books in the database.
     *
     * @return array An array of unique authors from active books.
     */
    public function getAuthors(): array {
        return $this->book::active()->distinct()->pluck('author')->values()->toArray();
    }

    /**
     * List all publication years of active books.
     *
     * This method retrieves a distinct list of publication years from the active books in the database.
     *
     * @return array An array of unique publication years from active books.
     */
    public function getPublicationYears(): array {
        return $this->book::active()->distinct()->pluck('publication')->values()->toArray();
    }

    /**
     * List all categories of active books.
     *
     * This method retrieves a unique list of categories associated with active books in the database.
     *
     * @return array An array of unique category titles from active books.
     */
    public function getCategories(): array {
        return $this->book::whereHas('category', function ($query) {
            $query->active();
        })
        ->with('category')
        ->get()
        ->pluck('category.title')
        ->unique()
        ->values()
        ->toArray();
    }
}
