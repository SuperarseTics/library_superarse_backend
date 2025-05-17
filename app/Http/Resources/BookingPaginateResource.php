<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingPaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'booking_code' => $this->uuid,
            'book_category' => $this->book->category->title,
            'book_title' => $this->book->title,
            'book_code' => $this->book->code,
            'booking_date' => $this->booking_date->toDateTimeString() ?? '-',
            'delivery_date' => $this->delivery_date ? $this->delivery_date->toDateTimeString() : '-',
            'giveback_date' => $this->giveback_date ? $this->giveback_date->toDateTimeString() : '-',
            'status' => $this->status
        ];
    }
}
