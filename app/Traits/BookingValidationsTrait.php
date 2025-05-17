<?php

namespace App\Traits;

use App\Models\Booking;
use App\Models\Setting;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

trait BookingValidationsTrait
{
    /**
     * Validates the loan request based on system settings for maximum books and loan duration.
     *
     * This method checks two conditions:
     * 1. If the user has already reached the maximum allowed number of books on loan.
     * 2. If the requested loan period exceeds the maximum allowed loan days.
     *
     * If either condition fails, a JSON response is returned with a clear message and HTTP 422 status code.
     * If both conditions pass, null is returned to indicate that the loan can proceed.
     *
     * @return JsonResponse|null A JSON response if validation fails, or null if validation passes.
     */
    protected function validateLoan(): JsonResponse|null {
        // Fetch system settings in a single query and decode 'properties' JSON
        $settings = Setting::where('section', 'system')->value('properties');

        // Extract max loan books and max loan days from settings
        $maxLoanBooks = $settings['max_loan_books'];

        // Check if user has exceeded the maximum number of books allowed on loan
        $userBooksCount = Auth::user()->books_on_loan->count();
        if ($maxLoanBooks && $userBooksCount >= $maxLoanBooks) {
            return GeneralHelper::response(
                __('messages.max_loan_books', ['maxLoanBooks' => $maxLoanBooks]),
                [],
                422
            );
        }

        // If both conditions pass, null is returned to indicate validation success
        return null;
    }

    /**
     * Checks if a booking exists and returns an error response if not.
     *
     * This method verifies whether a given booking object is null. If the booking does not exist,
     * it returns a JSON response with a message indicating that the booking was not found.
     * If the booking exists, it returns null.
     *
     * @param Booking|null $booking The booking object to check, or null if not found.
     * @return JsonResponse|null A JSON response with an error message if booking is not found, or null if it exists.
     */
    protected function existsBooking(?Booking $booking): JsonResponse|null
    {
        if (!$booking) {
            // Return a conflict response (HTTP 409) if the booking is not found
            return GeneralHelper::response(
                __('messages.reserve_not_found'),
                [],
                409
            );
        }

        // Return null if the booking exists
        return null;
    }
}
