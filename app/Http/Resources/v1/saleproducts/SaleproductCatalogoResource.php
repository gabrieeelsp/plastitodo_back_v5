<?php

namespace App\Http\Resources\v1\saleproducts;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleproductCatalogoResource extends JsonResource
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
            'type' => 'catalogos',
            'attributes' => [
                'name' => $this->name,
                'color' => $this->color,
            ],
        ]; 
    }
}
