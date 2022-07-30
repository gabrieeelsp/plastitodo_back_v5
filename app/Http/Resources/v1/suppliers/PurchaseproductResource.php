<?php

namespace App\Http\Resources\v1\suppliers;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseproductResource extends JsonResource
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
            'attributes' => [
                'name' => $this->name,
                'relacion_compra_stock' => $this->relacion_compra_stock,
                'is_enable' => $this->is_enable,
            ],
            'relationships' => [
                'stockproduct' => [
                    'id' => $this->stockproduct->id,
                    'attributes' => [
                        'name' => $this->stockproduct->name
                    ]
                ]
                
            ]
        ];
    }
}
