<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'user' => $this->user->name,
            'book_category' => $this->book->category->title,
            'book_code' => $this->book->code,
            'book_title' => $this->book->title,
            'booking_date' => $this->booking_date->toDateTimeString() ?? '-',
            'delivery_date' => $this->delivery_date ? $this->delivery_date->toDateTimeString() : '-',
            'giveback_date' => $this->giveback_date ? $this->giveback_date->toDateTimeString() : '-',
            'last_giveback_date' => $this->last_giveback_date->toDateTimeString(),
            'status' => $this->status
        ];
    }
}
