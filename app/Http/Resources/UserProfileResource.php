<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'phone_number2' => $this->phone_number2,
            'profile_picture' => env('IMG_BASE_LINK') . $this->profile_picture,
            'gender' => $this->gender,
            'age' => $this->age,
        ];
        return parent::toArray($request);
    }
}
