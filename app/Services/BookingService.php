<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Booking;
use App\Models\Setting;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;
use App\Repositories\BookRepository;
use App\Traits\BookValidationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use App\Repositories\BookingRepository;
use App\Traits\BookingValidationsTrait;
use App\Http\Requests\Booking\ShowRequest;
use App\Http\Requests\Booking\ReserveRequest;
use App\Http\Requests\Booking\DeliveryRequest;
use App\Http\Requests\Booking\GivebackRequest;
use App\Http\Resources\BookingPaginateResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingService
{
    use BookingValidationsTrait;
    use BookValidationsTrait;

    protected $bookingRepository;
    protected $bookRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        BookRepository $bookRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Retrieve a paginated list of bookings based on filtering criteria.
     * This function filters bookings by user, category, and code,
     * then returns the paginated result sorted by specified order and sort direction.
     *
     * @param int $page The current page number for pagination.
     * @param int $pageSize The number of records to display per page.
     * @param string $pageOrder The column name to order the results by.
     * @param string $pageSort The sort direction (asc or desc).
     * @param string|null $fCategory The category of the books to filter by (optional).
     * @param string|null $fCode The code of the books to filter by (optional).
     *
     * @return AnonymousResourceCollection Returns a JSON response containing the paginated bookings.
     */
    public function record(
        int $page,
        int $pageSize,
        string $pageOrder,
        string $pageSort,
        ?string $fCategory,
        ?string $fCode
    ): AnonymousResourceCollection
    {
        // Retrieve bookings filtered by user, category, and code, and apply ordering
        return BookingPaginateResource::collection(Booking::filterByUser(Auth::id())
        ->filterByBookCategory($fCategory)
        ->filterByBookCode($fCode)
        ->orderBy($pageOrder, $pageSort)
        ->paginate($pageSize, ['*'], 'page', $page));
    }

    /**
     * Display the booking details based on the provided code (UUID).
     *
     * This method handles the process of searching for a booking by its unique identifier (UUID),
     * checking if it exists, and returning the booking data in a structured JSON response.
     * If the booking does not exist, it throws an appropriate exception.
     *
     * @param ShowRequest $request The validated request containing the booking code (UUID).
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the booking data.
     */
    public function show(ShowRequest $request)
    {
        // Search for the booking using the UUID
        $booking = $this->bookingRepository->findByUuid($request->code);

        // Check if the booking exists, throws exception if not
        $this->existsBooking($booking);

        // Return a successful JSON response with the booking data
        return GeneralHelper::response(
            null,
            new BookingResource($booking),
            201
        );
    }

    /**
     * Handles the reservation of a book.
     *
     * This method processes a book reservation request by validating the book's existence,
     * checking its stock availability, and ensuring the user can loan it.
     * It creates a booking record in the database upon successful validation.
     *
     * @param ReserveRequest $request The incoming reservation request containing book and user data.
     *
     * @return JsonResponse JSON response containing the reservation confirmation message and booking details.
     */
    public function reserve(ReserveRequest $request): JsonResponse
    {
        // Search for the book in the database using its unique code
        $book = $this->bookRepository->findByCode($request->book_code);

        // Validate if the book exists and if there is stock available
        $this->existsBook($book);
        $this->checkStock($book);

        // Validate if the authenticated user can loan the book
        $this->validateLoan();

        // Create a booking record for the book and the user data
        $maxLoanDays = Setting::where('section','system')->value('properties')['max_loan_days'] ?? 0;
        $booking = Booking::create([
            'book_id' => $book->id,
            'booking_date' => $request->booking_date,
            'last_giveback_date' => Carbon::parse($request->booking_date)->addDays($maxLoanDays)
        ]);
        
        Book::where('code', $request->book_code)->decrement('stock', 1);

        // Return a success response with booking details and HTTP status 201 (Created)
        return GeneralHelper::response(
            __('messages.reserve_success'),
            new BookingResource($booking),
            201
        );
    }

    /**
     * Handles the delivery of a reserved book.
     *
     * This method updates the status of a booking to mark the book as delivered. It first checks if the
     * booking exists using the provided UUID. If the booking is found, it updates the booking's status
     * and saves the changes in the database. A success message is returned upon completion.
     *
     * @param DeliveryRequest $request The unique identifier (UUID) of the booking to deliver.
     *
     * @return JsonResponse A JSON response with a success message and the updated booking details.
     */
    public function delivery(DeliveryRequest $request): JsonResponse|null
    {
        // Search for the booking using the UUID
        $booking = $this->bookingRepository->findByUuid($request->booking_code);

        // Check if the booking exists, throws exception if not
        $this->existsBooking($booking);

        // Update the booking status to 'delivered'
        $booking->status = config('constants.states.deliver');
        $booking->delivery_date = now();
        $booking->save();

        // Return a success response with the updated booking details
        return GeneralHelper::response(
            __('messages.deliver_success'),
            new BookingResource($booking),
            201
        );
    }

    /**
     * Handles the return of a reserved book.
     *
     * This method updates the status of a booking to indicate that the book has been returned.
     * It first checks if the booking exists using the provided UUID. If the booking is found,
     * it updates the status to 'giveback' and saves the changes to the database. A success
     * message is returned upon completion.
     *
     * @param GivebackRequest $request The unique identifier (UUID) of the booking to mark as returned.
     *
     * @return JsonResponse|null A JSON response with a success message and the updated booking details, or null if the booking does not exist.
     */
    public function giveback(GivebackRequest $request): JsonResponse|null
    {
        // Search for the booking using the UUID
        $booking = $this->bookingRepository->findByUuid($request->booking_code);

        // Check if the booking exists, throws exception if not
        $this->existsBooking($booking);

        // Update the booking status to 'returned'
        $booking->status = config('constants.states.giveback');
        $booking->giveback_date = now();
        $booking->save();

        Book::where('code', $request->book_code)->increment('stock', 1);

        // Return a success response with the updated booking details
        return GeneralHelper::response(
            __('messages.giveback_success'),
            new BookingResource($booking),
            201
        );
    }
}
