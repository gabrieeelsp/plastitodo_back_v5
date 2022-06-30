<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Saleproduct;
use Illuminate\Http\Request;

use App\Http\Resources\v1\saleproducts\SaleproductResource;

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
}
