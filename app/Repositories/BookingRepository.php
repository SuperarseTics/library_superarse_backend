<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    protected $booking;

    public function __construct(Booking $booking) {
        $this->booking = $booking;
    }

    /**
     * Find a booking by its UUID.
     *
     * This method retrieves a booking from the database that matches the provided UUID.
     *
     * @param string $uuid The UUID of the booking to be retrieved.
     * @return null|Booking Returns the booking instance if found, or null if not found.
     */
    public function findByUuid(string $uuid): ?Booking {
        return $this->booking::where('uuid', $uuid)->first();
    }
}
