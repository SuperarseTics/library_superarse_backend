<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->title,
            'code' => $this->code,
            'title' => $this->title,
            'cover' => $this->cover,
            'author' => $this->author,
            'publication' => $this->publication,
            'synopsis' => $this->synopsis,
            'edition' => $this->edition,
            'stock' => $this->stock,
            'status' => $this->status ? 'Activo' : 'Inactivo'
        ];
    }
}
