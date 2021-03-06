<?php

namespace App\Http\Resources\v1\suppliers;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'type' => 'suppliers',
            'attributes' => [
                'name' => $this->name,
            ],
            'relationships' => [
                'purchaseproducts' => PurchaseproductResource::collection($this->purchaseproducts),
            ]
        ];
    }
}
