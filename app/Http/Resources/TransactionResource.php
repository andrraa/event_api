<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'date' => $this->date,
            'event_id' => $this->event_id,
            'ticket_id' => $this->ticket_id,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'event' => new MasterEventResource($this->whenLoaded('masterEvent')),
            'ticket' => new TicketResource($this->whenLoaded('ticket'))
        ];
    }
}
