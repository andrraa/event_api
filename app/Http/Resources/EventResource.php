<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'location' => $this->location,
            'description' => $this->description,
            'information' => $this->information,
            'image' => $this->image,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'event' => new MasterEventResource($this->whenLoaded('masterEvent')),
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
