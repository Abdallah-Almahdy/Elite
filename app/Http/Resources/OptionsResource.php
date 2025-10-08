<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'option_id' => $this->id,
            'option_name' => $this->name,
            'option_details' => OptionsValuesResource::collection($this->values),

        ];
    }
}
