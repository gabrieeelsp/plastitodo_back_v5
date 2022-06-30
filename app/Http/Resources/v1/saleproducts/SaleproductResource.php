<?php

namespace App\Http\Resources\v1\saleproducts;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleproductResource extends JsonResource
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
            'type' => 'saleproducts',
            'attributes' => [
                'name' => $this->name,
                'relacion_venta_stock' => $this->relacion_venta_stock,
            ],
            'relationships' => [
                'stockproduct' => [
                    'data' => [
                        'id' => $this->stockproduct_id,
                        'type' => 'stockproducts',
                        'attributes' => [
                            'name' => $this->stockproduct->name
                        ]
                    ]
                ]
            ]
        ];
    }
}
