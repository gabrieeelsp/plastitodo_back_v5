<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Stockproduct;
use App\Models\Ivaaliquot;
use App\Models\Combo;
use Illuminate\Http\Request;

use App\Http\Resources\v1\stockproducts\StockproductResource;
use App\Http\Resources\v1\stockproducts\StockproductStockResource;

use App\Http\Requests\v1\stockproducts\CreateStockproductRequest;

use Illuminate\Support\Facades\DB;

class StockproductController extends Controller
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

        if ( $request->has('ivaaliquot_id') ) {
            array_push($atr, ['ivaaliquot_id', '=', $request->get('ivaaliquot_id')] );
        }

        $limit = 50;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $items = Stockproduct::orderBy('name', 'ASC')
            ->where($atr)
            ->paginate($limit);
        
        return StockproductResource::collection($items);
    }
    public function get_stock(Request $request)
    {   

        $searchText = trim($request->get('q'));
        $val = explode(' ', $searchText);
        $atr = [];
        foreach($val as $q) {
            array_push($atr, ['name', 'LIKE', '%'.strtolower($q).'%']);
        };

        $limit = 50;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $items = Stockproduct::orderBy('name', 'ASC')
            ->where($atr)
            ->paginate($limit);
        
        return StockproductStockResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStockproductRequest $request)
    {
        $data = $request->get('data');
        $ivaaliquot_id = $data['relationships']["ivaaliquot"]["data"]["id"];

        $stockproduct = Stockproduct::create($request->input('data.attributes'));

        $stockproduct->ivaaliquot()->associate($ivaaliquot_id);
        $stockproduct->save();

        return new StockproductResource($stockproduct);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stockproduct  $stockproduct
     * @return \Illuminate\Http\Response
     */
    public function show(Stockproduct $stockproduct)
    {
        return new StockproductResource($stockproduct);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stockproduct  $stockproduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stockproduct $stockproduct)
    {        
        $combos_to_update = [];

        try {
            DB::beginTransaction();

            $stockproduct->update($request->input('data.attributes'));

            if ( $request->has('data.relationships.ivaaliquot')) {
                $stockproduct->ivaaliquot_id = $request->get('data')['relationships']['ivaaliquot']['id'];
            }

            foreach ( $stockproduct->saleproducts as $itemSaleproduct ) {
                $itemSaleproduct->set_precios($request->get('data')['attributes']['costo']);
                $itemSaleproduct->save();

                foreach ( $itemSaleproduct->comboitems as $comboitem ) {
                    if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                        array_push($combos_to_update, $comboitem->combo_id);
                    }
                }
            }

            $stockproduct->save();

            $stockproduct_saved = Stockproduct::find($stockproduct->id);
            
            $combos = Combo::whereIn('id', $combos_to_update)->get();
            foreach ( $combos as $combo ) {
                $combo->setPrecios();
                $combo->save();
            }
            
            DB::commit();
            return new StockproductResource($stockproduct_saved);

        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function update_values(Request $request, $stockproduct_id)
    {
        $stockproduct = Stockproduct::findOrFail($stockproduct_id);
        $combos_to_update = [];
        try {

            DB::beginTransaction();

            if ( $request->has('data.update_group')) {
                if ( $request->get('data')['update_group'] == true ) {
                    if ( $stockproduct->stockproductgroup ) {
                        $stockproducts_group = Stockproduct::where('stockproductgroup_id', $stockproduct->stockproductgroup_id)->get();

                        $data = $request->get('data');
                        foreach ( $stockproducts_group as $itemGroup ) {

                            $itemGroup->costo = $data['attributes']['costo'];

                            foreach ( $itemGroup->saleproducts as $itemSaleproduct ) {
                                $itemSaleproduct->set_precios($data['attributes']['costo']);
                                $itemSaleproduct->save();

                                foreach ( $itemSaleproduct->comboitems as $comboitem ) {
                                    if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                                        array_push($combos_to_update, $comboitem->combo_id);
                                    }
                                }
                            }

                            $itemGroup->save();
                            
                        }
                        $combos = Combo::whereIn('id', $combos_to_update)->get();
                        foreach ( $combos as $combo ) {
                            $combo->setPrecios();
                            $combo->save();
                        }
                        DB::commit();
                        return StockproductResource::collection($stockproducts_group);

                    }
                }
            }

            $data = $request->get('data');
            $stockproduct->costo = $data['attributes']['costo'];
            foreach ( $stockproduct->saleproducts as $itemSaleproduct ) {
                $itemSaleproduct->set_precios($data['attributes']['costo']);
                $itemSaleproduct->save();

                foreach ( $itemSaleproduct->comboitems as $comboitem ) {
                    if ( !in_array($comboitem->combo_id, $combos_to_update ) ) {
                        array_push($combos_to_update, $comboitem->combo_id);
                    }
                }
            }
            $stockproduct->save();

            $stockproduct_saved = Stockproduct::find($stockproduct->id);

            $combos = Combo::whereIn('id', $combos_to_update)->get();
            foreach ( $combos as $combo ) {
                $combo->setPrecios();
                $combo->save();
            }

            DB::commit();
            return new StockproductResource($stockproduct_saved);

        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stockproduct  $stockproduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stockproduct $stockproduct)
    {
        //
    }

    public function get_stockproducts_select(Request $request)
    {

        $searchText = trim($request->get('q'));
        $val = explode(' ', $searchText );
        $atr = [];
        foreach ($val as $q) {
            array_push($atr, ['name', 'LIKE', '%'.strtolower($q).'%'] );
        };

        $limit = 10;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $stockproducts = DB::table('stockproducts')
                            ->where($atr)
                            ->select(
                                'stockproducts.name',
                                'stockproducts.id',
                                'stockproducts.image',
                            )
                            ->orderBy('name', 'ASC')
                            ->paginate($limit);
        return $stockproducts;
    }

    public function remove_image(Request $request, $stockproduct_id)
    {

        $stockproduct = Stockproduct::findOrFail($stockproduct_id);
        
        $stockproduct->image = null;

        $stockproduct->save();

        return new StockproductResource($stockproduct);
    }

    public function updload_image(Request $request, $stockproduct_id)
    {
        usleep(1000000);
        $stockproduct = Stockproduct::findOrFail($stockproduct_id);

        $request->validate([

            'image' => 'required|image',
        ]);
        

        $url_image = $this->upload($request->file('image'));
        $stockproduct->image = $url_image;

        $stockproduct->save();

        return new StockproductResource($stockproduct);
    }

    private function upload($image)
    {
        $path_info = pathinfo($image->getClientOriginalName());
        $post_path = 'images/stockproducts';

        $rename = uniqid() . '.' . $path_info['extension'];
        $image->move(public_path() . "/$post_path", $rename);
        return "$post_path/$rename";
    }
}
