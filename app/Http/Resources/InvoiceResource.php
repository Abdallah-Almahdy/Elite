<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'total' => $this->total,
            'address' => $this->address,
            'payment_method' => $this->payments->map(function ($payment) {
                return [
                    'method' => $payment->payment_method,
                    'amount' => $payment->amount
                ];
            }),
            'cashier_name' => $this->cashier->name,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->product->name,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                    'subtotal' => $product->subtotal,
                    'unit_conversion_factor' => $product->unit_conversion_factor
                ];
            }),


        ];
    }
}
