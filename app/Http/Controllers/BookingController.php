<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\ShowRequest;
use App\Http\Requests\Booking\RecordRequest;
use App\Http\Requests\Booking\ReserveRequest;
use App\Http\Requests\Booking\DeliveryRequest;
use App\Http\Requests\Booking\GivebackRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function record(RecordRequest $request): AnonymousResourceCollection
    {
        return $this->bookingService->record(
            $request->page,
            $request->size,
            $request->order,
            $request->sort,
            $request->f_category,
            $request->f_code
        );
    }

    public function show(ShowRequest $request): JsonResponse
    {
        return $this->bookingService->show($request);
    }

    public function reserve(ReserveRequest $request): JsonResponse
    {
        return $this->bookingService->reserve($request);
    }

    public function delivery(DeliveryRequest $request): JsonResponse
    {
        return $this->bookingService->delivery($request);
    }

    public function giveback(GivebackRequest $request): JsonResponse
    {
        return $this->bookingService->giveback($request);
    }
}
