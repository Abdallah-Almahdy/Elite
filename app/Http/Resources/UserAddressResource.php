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
            'user_id' => $this->user_id ,
            'address_city' => $this->address_city ?? null,
            'address_street' => $this->address_street ?? null,
            'address_building' => $this->address_building ?? null,
            'address_floor' => $this->address_floor ?? null,
            'address_apartment' => $this->address_apartment ?? null,
            'delivery_place_id' => $this->delivery_place_id,
            'address_governorate'=> $this->address_governorate,
            'is_default' => $this->is_default,
        ];
        return parent::toArray($request);
    }
}
