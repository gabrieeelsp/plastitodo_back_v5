<?php

namespace App\Http\Resources\v1\sales;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class ComprobanteSaleResource extends JsonResource
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
                'punto_venta' => $this->punto_venta,
                'numero' => $this->numero,
                'cae' => $this->cae,
                'cae_fch_vto' => $this->cae_fch_vto ? date('d / m / Y', Carbon::parse($this->cae_fch_vto)->timestamp) : null,
                'tipo' => $this->modelofact->name,
                'id_afip_tipo' => $this->id_afip_tipo,
                'created_at' => date('d / m / Y', $this->created_at->timestamp),
            ],
            'relationships' => [
                'modelofact' => [
                    'id' => $this->modelofact->id,
                    'name' => $this->modelofact->name
                ]
            ]       
            
        ]; 
    }
}
