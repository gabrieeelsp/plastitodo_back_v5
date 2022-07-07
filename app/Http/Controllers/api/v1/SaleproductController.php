<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Saleproduct;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\v1\saleproducts\SaleproductResource;
use App\Http\Resources\v1\saleproducts\SaleproductVentaResource;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Saleproduct  $saleproduct
     * @return \Illuminate\Http\Response
     */
    public function show(Saleproduct $saleproduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Saleproduct  $saleproduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saleproduct $saleproduct)
    {
        //
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
                                'saleproducts.porc_min as valor_1',
                                'saleproducts.porc_may as valor_2',
                                'saleproducts.relacion_venta_stock as valor_3',                                                     
                                'stockproducts.costo as valor_4',
                                'saleproducts.stockproduct_id as valor_5', 
                                'stockproducts.is_stock_unitario_variable as valor_6',
                                'stockproducts.stock_aproximado_unidad as valor_7'
                            )
                            ->addSelect(DB::raw("'saleproduct' as tipo"));

        $combos = DB::table('combos')
                            ->where($atr_combo)
                            ->select(
                                'combos.name',
                                'combos.id',
                                'combos.precio_min as valor_1',
                                'combos.precio_may as valor_2',
                                'combos.precio_min as valor_3',
                                'combos.precio_min as valor_4',
                                'combos.precio_may as valor_5',
                                'combos.precio_min as valor_6',
                                'combos.precio_min as valor_7',
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
