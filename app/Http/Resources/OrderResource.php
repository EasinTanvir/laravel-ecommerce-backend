<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'total_amount' => $this->total_amount,

            'status' => $this->status,

            'shipping_address' => $this->shipping_address,

            'created_at' => $this->created_at,

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],

            'payment' => [
                'id' => $this->payment?->id,
                'amount' => $this->payment?->amount,
                'payment_method' => $this->payment?->payment_method,
                'status' => $this->payment?->status,
            ],

            'products' => $this->products->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->pivot->price,
                    'quantity' => $product->pivot->quantity,
                    'subtotal' => $product->pivot->price * $product->pivot->quantity,
                ];
            }),
        ];
    }
}