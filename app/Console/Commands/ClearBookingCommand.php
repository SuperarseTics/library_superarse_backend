<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearBookingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete bookings older than two days based on booking date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $twoDaysAgo = $now->subDays(2);

        $bookingsToDelete = Booking::where('booking_date', '<', $twoDaysAgo)->get();

        $deletedCount = $bookingsToDelete->count();
        foreach ($bookingsToDelete as $booking) {
            $booking->book()->increment('stock', 1);
            $booking->delete();
        }

        Log::info("Report {$now->toDateTimeString()}: Deleted {$deletedCount} booking(s) older than two days.");
    }
}
