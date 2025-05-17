<?php

namespace App\Services;

use App\Models\Book;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BookResource;
use App\Repositories\BookRepository;
use App\Traits\BookValidationsTrait;
use App\Http\Requests\Book\ShowRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookService
{
    use BookValidationsTrait;

    protected $bookRepository;

    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Retrieve filter data for books.
     * This function generates filter options such as authors, publication years, and categories
     * from the book repository, and returns them in a JSON response.
     *
     * @return JsonResponse Returns a JSON response containing the filter data.
     */
    public function filter(): JsonResponse
    {
        // Generate filter data for authors, publication years, and categories
        $data = [
            'authors' => $this->bookRepository->getAuthors(),
            'publication' => $this->bookRepository->getPublicationYears(),
            'categories' => $this->bookRepository->getCategories()
        ];

        // Return a successful JSON response with the filter data
        return GeneralHelper::response(
            null,
            $data,
            200
        );
    }

    /**
     * Retrieve a paginated catalog of books with filtering options.
     * This function allows filtering the book catalog by category, author, and publication year,
     * and supports pagination and sorting based on the specified parameters.
     *
     * @param int $page The page number to retrieve.
     * @param int $pageSize The number of books to display per page.
     * @param string $pageOrder The column by which the books should be ordered.
     * @param string $pageSort The sort direction ('asc' or 'desc').
     * @param string|null $fCategory (Optional) The category filter to apply.
     * @param string|null $fAuthor (Optional) The author filter to apply.
     * @param string|null $fTitle (Optional) The title filter to apply.
     * @param int|null $fPublication (Optional) The publication year filter to apply.
     *
     * @return AnonymousResourceCollection
     */
    public function catalog(
        int $page,
        int $pageSize,
        string $pageOrder,
        string $pageSort,
        ?string $fCategory,
        ?string $fAuthor,
        ?string $fTitle,
        ?int $fPublication
    ): AnonymousResourceCollection
    {
        // Filter books by category, author, and publication year, and apply pagination and sorting
        return BookResource::collection(Book::filterByCategory($fCategory)
        ->filterByAuthor($fAuthor)
        ->filterByTitle($fTitle)
        ->filterByPublication($fPublication)
        ->active()
        ->orderBy($pageOrder, $pageSort)
        ->paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Display a specific book by its code.
     * This function searches for a book in the database by its unique code, validates
     * if the book exists, and returns the book data in a JSON response.
     *
     * @param ShowRequest $request The request containing the book code to be retrieved.
     *
     * @return JsonResponse Returns a JSON response containing the book data or an error message if not found.
     */
    public function show(ShowRequest $request): JsonResponse
    {
        // Search for the book in the database by its code
        $book = $this->bookRepository->findByCode($request->code);

        // Validate if the book exists, throw an error if it does not
        $this->existsBook($book);

        // Return a successful JSON response with the book data
        return GeneralHelper::response(
            null,
            new BookResource($book),
            201
        );
    }

    /**
     * Store a new book in the database.
     * This function handles storing a new book by processing the incoming request, uploading the book's cover image,
     * saving the file with its original name in the specified folder, and storing the book's data in the database.
     *
     * @param StoreRequest $request The request containing the book's data, including the cover image file.
     *
     * @return JsonResponse Returns a JSON response with the newly created book data and a success message.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        // Capture all data from the request into an array
        $data = $request->toArray();

        // Retrieve the original name of the uploaded cover image file
        $filename = $request->file('cover')->getClientOriginalName();

        // Save the cover image in the 'covers' directory with the original filename, under a folder named by the book's code
        $path = $request->file('cover')->storeAs("covers/book-{$data['code']}", $filename, 'public');

        // Add the public URL of the uploaded image to the data array for storage
        $data['cover'] = url(Storage::url($path));

        // Create a new book in the database with the provided data and return a success response
        return GeneralHelper::response(
            __('messages.book_created_success'),
            new BookResource(Book::create($data)),
            201
        );
    }

    /**
     * Update an existing book in the database.
     * This function updates the details of an existing book, including the option to update the book's cover image.
     * It processes the incoming request, validates the book's existence, optionally uploads a new cover image,
     * and updates the book's data in the database.
     *
     * @param UpdateRequest $request The request containing the book's updated data, including an optional new cover image.
     *
     * @return JsonResponse Returns a JSON response with the updated book data and a success message.
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        // Capture all data from the request into an array
        $data = $request->toArray();

        // Search for the book in the database by its ID
        $book = $this->bookRepository->findById($data['id']);

        // Validate if the book exists
        $this->existsBook($book);

        // Check if a new cover file has been uploaded
        if ($request->hasFile('cover')) {
            // Retrieve the original name of the uploaded cover image file
            $filename = $request->file('cover')->getClientOriginalName();

            // Save the new cover image in the 'covers' directory with the original filename
            $path = $request->file('cover')->storeAs("covers/book-{$data['code']}", $filename, 'public');

            // Update the cover URL in the data array
            $data['cover'] = url(Storage::url($path));
        }

        // Update the book's details in the database with the provided data
        $book->update($data);

        // Return a success response with the updated book data
        return GeneralHelper::response(
            __('messages.book_updated_success'),
            new BookResource($book),
            201
        );
    }

    /**
     * Delete a book from the database.
     * This function searches for a book by its code, validates its existence,
     * and then deletes it from the database. It returns a JSON response indicating the result of the operation.
     *
     * @param string $code The unique code of the book to be deleted.
     *
     * @return JsonResponse Returns a JSON response with a success message upon deletion.
     */
    public function destroy(string $code): JsonResponse
    {
        // Search for the book in the database using its code
        $book = $this->bookRepository->findByCode($code);

        // Validate if the book exists
        $this->existsBook($book);

        // Delete the book from the database
        $book->delete();

        // Return a success response indicating the book was deleted
        return GeneralHelper::response(
            __('messages.book_destroyed_success'),
            [],
            201
        );
    }

    /**
     * Download the book catalog with filtering options.
     * This function allows filtering the book catalog by category, author, and publication year,
     * and download it.
     *
     * @param string|null $fCategory (Optional) The category filter to apply.
     * @param string|null $fAuthor (Optional) The author filter to apply.
     * @param string|null $fTitle (Optional) The title filter to apply.
     * @param int|null $fPublication (Optional) The publication year filter to apply.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(
        ?string $fCategory,
        ?string $fAuthor,
        ?string $fTitle,
        ?int $fPublication
    )
    {
        // Filter books by category, author, and publication year
        $data = $this->bookRepository->getAllBooks($fCategory, $fAuthor, $fTitle, $fPublication);

        // Csv file name
        date_default_timezone_set('America/Guayaquil');
        $formattedDate = date('YmdHis');
        $fileName = "catalogo_libros_{$formattedDate}.csv";

        // Header names
        $headerNames = [
            "category_title"    => "CATEGORY",
			"code"              => "CODE",
			"title"             => "TITLE",
			"author"            => "AUTHOR",
			"publication"       => "PUBLICATION",
            "edition"           => "EDITION",
			"stock"             => "STOCK",
			"status"            => "STATUS"
		];
        
        // Build the full path inside storage/downloads
        $filePath = storage_path("app/public/downloads/{$fileName}");
        
        // File creation
        $fp = fopen($filePath, "wb");
        fwrite($fp, "\xEF\xBB\xBF");
        fputcsv($fp, $headerNames, ";", '"', "\\");
        foreach ($data as $colsValues) {
            $auxColsValues = [];
            foreach ($headerNames as $key => $value) {
                if ($key == 'status') {
                    $auxColsValues[$key] = $colsValues->{$key} ? 'Active' : 'Inactive';
                }else {
                    $auxColsValues[$key] = $colsValues->{$key};
                }
            }
            fputcsv($fp, $auxColsValues, ";", '"');
        }
        fclose($fp);

        // Return the CSV file as a download response
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);        

    }
}
