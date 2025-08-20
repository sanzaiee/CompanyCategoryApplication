<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'description' => $this->description,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category'))
        ];
    }
}
