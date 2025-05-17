<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Booking;
use App\Models\Category;
use App\Repositories\CategoryRepository;

class DashboardService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Generate a report with key statistics for books, categories, and bookings.
     *
     * This method generates various counts and metrics related to books, categories,
     * bookings, and reservations. It provides an overview of:
     * - Total active books
     * - Total active categories
     * - Total bookings
     * - Total books not returned
     * - Number of books per category
     * - Top 5 most reserved books
     * - Monthly reservations count for the current year
     *
     * The results are aggregated and returned in a structured array, optimized for reporting purposes.
     *
     * @return array An associative array containing the report data.
     */
    public function generate()
    {
        // Get total count of active books
        $books = Book::active()->count();

        // Get total count of active categories
        $categories = Category::active()->count();

        // Get total count of bookings
        $bookings = Booking::count();

        // Get total count of bookings where the status is 'notgiveback'
        $notGiveBack = Booking::where('status', config('constants.notgiveback'))->count();

        // Get the number of books per category
        $booksPerCategory = Category::active()
            ->withCount('books')
            ->get()
            ->pluck('books_count', 'title'); // Map category titles to book counts

        // Get top 5 books with the most bookings
        $topReservedBooks = Book::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($book) {
                return [
                    'title' => $book->title,
                    'reservations' => $book->bookings_count
                ];
            });

        // Initialize an array with months of the current year, all set to 0 initially
        $months = [
            'January' => 0, 'February' => 0, 'March' => 0,
            'April' => 0, 'May' => 0, 'June' => 0,
            'July' => 0, 'August' => 0, 'September' => 0,
            'October' => 0, 'November' => 0, 'December' => 0,
        ];

        // Get the number of bookings per month for the current year
        $reservationsPerMonth = Booking::selectRaw('MONTH(booking_date) as month, COUNT(*) as total')
            ->whereYear('booking_date', now()->year)  // Limit to current year bookings
            ->groupBy('month')                        // Group by month
            ->orderBy('month')                        // Sort by month (ascending)
            ->get()
            ->mapWithKeys(function ($booking) {
                return [date('F', mktime(0, 0, 0, $booking->month, 10)) => $booking->total];
            });

        // Merge the reservation data into the $months array
        foreach ($reservationsPerMonth as $month => $total) {
            $months[$month] = $total;
        }

        // Return the complete report data in a structured array
        return [
            'books' => $books,
            'categories' => $categories,
            'bookings' => $bookings,
            'notGiveBack' => $notGiveBack,
            'booksPerCategory' => $booksPerCategory,
            'topReservedBooks' => $topReservedBooks,
            'reservationsPerMonth' => $months
        ];
    }
}
