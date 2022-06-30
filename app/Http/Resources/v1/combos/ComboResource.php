<?php

namespace App\Http\Resources\v1\combos;

use Illuminate\Http\Resources\Json\JsonResource;

class ComboResource extends JsonResource
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
            'type' => 'combos',
            'attributes' => [
                'name' => $this->name,
                'descuento' => $this->descuento,
                'precio_min' => $this->getPrecioMin(),
            ],
            'relationships' => [
                'comboitems' => ComboitemResource::collection($this->comboitems),
            ]
        ];
    }
}
