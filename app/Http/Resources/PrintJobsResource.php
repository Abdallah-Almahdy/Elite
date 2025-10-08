<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintJobsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'job_id' => $this->id,
            'order_id' => $this->order_id,
            'printer_name' => $this->printer->model,
            'pdf_url' => $this->pdf_url,
            'status' => $this->status,
        ];;
    }
}
