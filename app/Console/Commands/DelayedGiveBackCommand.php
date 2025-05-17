<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Console\Command;
use App\Mail\AdminLateReturnMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingGiveBackReminderMail;

class DelayedGiveBackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delayed-give-back';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks bookings as delayed and sends a reminder to users and administrator.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $bookingsToSetNotGiveBack = Booking::where('last_give_back_date', '<', $now)->get();
        $bookingsChangedStatus = $bookingsToSetNotGiveBack->count();
        foreach ($bookingsToSetNotGiveBack as $booking) {
            $booking->status = config('constants.states.notgiveback');
            $booking->save();
        }

        Log::info("Report {$now->toDateTimeString()}: Change {$bookingsChangedStatus} booking(s) to delayed status.");

        $settingNotification = Setting::where('section', 'notifications')->value('properties');
        if ($settingNotification['last_day']) {
            $bookingsToNotify = Booking::where('status', config('constants.states.notgiveback'))->get();
            $bookingsNotified = $bookingsToNotify->count();
            foreach ($bookingsToNotify as $booking) {
                Mail::to($booking->user->email)->send(new BookingGiveBackReminderMail($booking));
                if ($settingNotification['email']) {
                    Mail::to($settingNotification['email'])->send(new AdminLateReturnMail($booking));
                }
            }

            Log::info("Report {$now->toDateTimeString()}: Notified {$bookingsNotified} booking(s) delayed to their emails.");
        }
    }
}
