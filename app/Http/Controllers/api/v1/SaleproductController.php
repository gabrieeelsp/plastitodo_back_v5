<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Saleproduct;
use App\Models\Combo;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;

use App\Http\Resources\v1\saleproducts\SaleproductResource;
use App\Http\Resources\v1\saleproducts\SaleproductVentaResource;

use App\Http\Requests\v1\saleproducts\CreateSaleproductRequest;

class SaleproductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchText = trim($request->get('q'));
        $val = explode(' ', $searchText);
        $atr = [];
        foreach($val as $q) {
            array_push($atr, ['name', 'LIKE', '%'.strtolower($q).'%']);
        };

        $limit = 5;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $items = Saleproduct::orderBy('name', 'ASC')
            ->where($atr)
            ->paginate($limit);
        
        return SaleproductResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSaleproductRequest $request)
    {
        $data = $request->get('data');
        $stockproduct_id = $data['relationships']["stockproduct"]["data"]["id"];

        $saleproduct = Saleproduct::create($request->input('data.attributes'));

        $saleproduct->stockproduct()->associate($stockproduct_id);
        $saleproduct->save();

        return new SaleproductResource($saleproduct);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Saleproduct  $saleproduct
     * @return \Illuminate\Http\Response
     */
    public function show(Saleproduct $saleproduct)
    {
        return new SaleproductResource($saleproduct);
    }

    public function search_barcode(Request $request)
    {
        $saleproduct = Saleproduct::where('barcode', $request->get('barcode'))->get()->first();
        return new SaleproductVentaResource($saleproduct);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Saleproduct  $saleproduct
     * @return \Illuminate\Http\Response
     */

    public function remove_image(Request $request, $saleproduct_id)
    {

        $saleproduct = Saleproduct::findOrFail($saleproduct_id);

        $request->validate([
            'order' => 'required|int'
        ]);
        
        if ( $request->get('order') == 1) {
            $saleproduct->image1 = null;
        }
        if ( $request->get('order') == 2) {
            $saleproduct->image2 = null;
        }
        if ( $request->get('order') == 3) {
            $saleproduct->image3 = null;
        }

        $saleproduct->save();

        return new SaleproductResource($saleproduct);
    }

    public function updload_image(Request $request, $saleproduct_id)
    {
        usleep(1000000);
        $saleproduct = Saleproduct::findOrFail($saleproduct_id);

        $request->validate([

            'image' => 'required|image',
            'order' => 'required|int'
        ]);
        

        $url_image = $this->upload($request->file('image'));
        if ( $request->get('order') == 1) {
            $saleproduct->image1 = $url_image;
        }
        if ( $request->get('order') == 2) {
            $saleproduct->image2 = $url_image;
        }
        if ( $request->get('order') == 3) {
            $saleproduct->image3 = $url_image;
        }

        $saleproduct->save();

        return new SaleproductResource($saleproduct);
    }

    private function upload($image)
    {
        $path_info = pathinfo($image->getClientOriginalName());
        $post_path = 'images/saleproducts';

        $rename = uniqid() . '.' . $path_info['extension'];
        $image->move(public_path() . "/$post_path", $rename);
        return "$post_path/$rename";
    }

    public function update_values(Request $request, $saleproduct_id)
    {   
        $saleproduct = Saleproduct::findOrFail($saleproduct_id);

        $combos_to_update = [];

        if ( $request->has('data.update_group')) {
            if ( $request->get('data')['update_group'] == true ) {
                if ( $saleproduct->saleproductgroup ) {
                    $saleproducts = Saleproduct::where('saleproductgroup_id', $saleproduct->saleproductgroup_id)->get();

                    $data = $request->get('data');
                    
                    try {
                        DB::beginTransaction();

                        foreach ( $saleproducts as $itemGroup ) {
                            $itemGroup->porc_min = $data['attributes']['porc_min'];
                            $itemGroup->porc_may = $data['attributes']['porc_may'];

                            $itemGroup->set_precios($itemGroup->stockproduct->costo);
                            $itemGroup->save();

                            foreach ( $saleproduct->comboitems as $comboitem ) {
                                if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                                    array_push($combos_to_update, $comboitem->combo_id);
                                }
                            }
                                                        
                        }

                        $combos = Combo::whereIn('id', $combos_to_update)->get();
                        foreach ( $combos as $combo ) {
                            $combo->setPrecios();
                            $combo->save();
                        }

                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                        return $e;
                    }
                    return SaleproductResource::collection($saleproducts);
                }
                

                
            }

        }
        $data = $request->get('data');

        try {
            DB::beginTransaction();

            $saleproduct->porc_min = $data['attributes']['porc_min'];
            $saleproduct->porc_may = $data['attributes']['porc_may'];
            
            $saleproduct->set_precios($saleproduct->stockproduct->costo);

            $saleproduct->save();

            foreach ( $saleproduct->comboitems as $comboitem ) {
                if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                    array_push($combos_to_update, $comboitem->combo_id);
                }
            }

            $combos = Combo::whereIn('id', $combos_to_update)->get();
            foreach ( $combos as $combo ) {
                $combo->setPrecios();
                $combo->save();
            }

            $saleproduct_saved = Saleproduct::find($saleproduct->id);

            DB::commit();
            return new SaleproductResource($saleproduct_saved);
            
        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function update(Request $request, Saleproduct $saleproduct)
    {   

        try {
            DB::beginTransaction();

            $saleproduct->update($request->input('data.attributes')); 

            $saleproduct->save();
            $saleproduct_saved = Saleproduct::find($saleproduct->id);
            $saleproduct_saved->set_precios($saleproduct->stockproduct->costo);
            $saleproduct_saved->save();

            $combos_to_update = [];
            foreach ( $saleproduct->comboitems as $comboitem ) {
                if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                    array_push($combos_to_update, $comboitem->combo_id);
                }
            }

            $combos = Combo::whereIn('id', $combos_to_update)->get();
            foreach ( $combos as $combo ) {
                $combo->setPrecios();
                $combo->save();
            }

            

            DB::commit();
            return new SaleproductResource($saleproduct_saved);
        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Saleproduct  $saleproduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saleproduct $saleproduct)
    {
        //
    }

    public function get_sale_products_venta(Request $request)
    {

        $searchText = trim($request->get('q'));
        $val = explode(' ', $searchText );
        $atr_saleproduct = [];
        foreach ($val as $q) {
            array_push($atr_saleproduct, ['saleproducts.name', 'LIKE', '%'.strtolower($q).'%'] );
        };

        $atr_combo = [];
        foreach ($val as $q) {
            array_push($atr_combo, ['combos.name', 'LIKE', '%'.strtolower($q).'%'] );
        };

        $limit = 10;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $saleproducts = DB::table('saleproducts')
                            ->where($atr_saleproduct)
                            ->join('stockproducts', 'saleproducts.stockproduct_id', '=', 'stockproducts.id')
                            ->select(
                                'saleproducts.name',
                                'saleproducts.id',
                                'saleproducts.precio_min',
                                'saleproducts.precio_may',

                                'stockproducts.is_stock_unitario_variable as is_stock_unitario_variable',
                                'stockproducts.stock_aproximado_unidad as stock_aproximado_unidad',

                                'saleproducts.desc_min as desc_min',
                                'saleproducts.desc_may as desc_may',
                                'saleproducts.fecha_desc_desde as fecha_desc_desde',
                                'saleproducts.fecha_desc_hasta as fecha_desc_hasta',
                                
                                'saleproducts.image1',
                                'saleproducts.image2',
                                'saleproducts.image3',

                                // 'saleproducts.name',
                                // 'saleproducts.id',
                                // 'saleproducts.porc_min as valor_1',
                                // 'saleproducts.porc_may as valor_2',
                                // 'saleproducts.relacion_venta_stock as valor_3',                                                     
                                // 'stockproducts.costo as valor_4',
                                // 'saleproducts.stockproduct_id as valor_5', 
                                // 'stockproducts.is_stock_unitario_variable as valor_6',
                                // 'stockproducts.stock_aproximado_unidad as valor_7',
                                // 'saleproducts.desc_min as valor_8',
                                // 'saleproducts.desc_may as valor_9',
                                // 'saleproducts.fecha_desc_desde as valor_10',
                                // 'saleproducts.fecha_desc_hasta as valor_11',
                            )
                            ->addSelect(DB::raw("'saleproduct' as tipo"));

        $combos = DB::table('combos')
                            ->where($atr_combo)
                            ->select(
                                'combos.name',
                                'combos.id',
                                'combos.precio_min',
                                'combos.precio_may',
                                'combos.precio_min as is_stock_unitario_variable',
                                'combos.precio_min as stock_aproximado_unidad',
                                'combos.precio_min as desc_min',
                                'combos.precio_may as desc_may',
                                'combos.precio_min as fecha_desc_desde',
                                'combos.precio_min as fecha_desc_hasta',

                                'combos.image1',
                                'combos.image2',
                                'combos.image3',
                            )
                            ->addSelect(DB::raw("'combo' as tipo"))
                            ->unionall($saleproducts)
                            ->orderBy('name', 'ASC')
                            ->paginate($limit);
        return $combos;

        
        $saleproducts = Saleproduct::orderBy('name', 'ASC')
            ->where($atr)->get();
            //->paginate($limit);

        //VENTA -----
        return SaleproductVentaResource::collection($saleproducts);
    }
}
