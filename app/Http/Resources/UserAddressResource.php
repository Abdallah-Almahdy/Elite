<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'user_id' => $this->user_id,
            'address_country' => $this->address_country,
            'address_city' => $this->address_city,
            'address_street' => $this->address_street,
            'address_building' => $this->address_building,
            'address_floor' => $this->address_floor,
            'address_apartment' => $this->address_apartment,
            'is_default' => $this->is_default,
        ];
        return parent::toArray($request);
    }
}
