<?php

namespace App\Http\Resources\v1\tags;

use Illuminate\Http\Resources\Json\JsonResource;

class TagListResource extends JsonResource
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
            'type' => 'tags',
            'attributes' => [
                'name' => $this->name,                
		        'color' => $this->color,
            ],
            'relationships' => [
                'saleproducts' => null,
                'clients' => null,
            ],
        ]; 
    }
}
