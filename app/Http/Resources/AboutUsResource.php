<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return
            [
                'id'                  => $this->id,
                'company_name'        => $this->company_name,
                'short_description'   => $this->short_description,
                'full_description'    => $this->full_description,
                'facebook'            => $this->facebook,
                'whatsapp'            => $this->whatsapp,
                'instagram'           => $this->instagram,
                'location'            => $this->location,
                'phone'               => $this->phone,
                'email'               => $this->email,
                'address'             => $this->address,
                'work_from'           => $this->work_from,
                'work_to'             => $this->work_to,
                'experience_years'    => $this->experience_years,
                'happy_clients'       => $this->happy_clients,
                'successful_projects' => $this->successful_projects,

                // اللوجو
                'logo_url' => $this->logo
                    ? asset('uploads/about/' . $this->logo)
                    : null,

                // الصور
                'photos' => $this->images->map(function ($image) {
                    return asset('uploads/about/photos/' . $image->photo);
                }),
           ];
    }
}

