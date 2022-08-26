<?php

namespace App\Http\Resources\v1\cajas;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundCajaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'refunds',
            'attributes' => [
                'created_at'=> $this->created_at,
                'valor' => $this->valor,
                'name' => $this->paymentmethod->name,
            ],
            'relationships' => [
                'paymentmethod' => [
                    'id' => $this->paymentmethod->id,
                    'name' => $this->paymentmethod->name,
                ]
            ]
        ];
    }
}