<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;
use App\Http\Resources\v1\cajas\CajaResource;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caja $caja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        //
    }

    public function find($sucursal_id) {
        $atr = [];

        array_push($atr, ['sucursal_id', $sucursal_id] );

        array_push($atr, ['is_open', true] );

        array_push($atr, ['user_id', auth()->user()->id] );

        $caja = Caja::where($atr)->firstOrFail();

        return new CajaResource($caja);
    }

    private function have_caja_open() {
        $atr = [];

        array_push($atr, ['is_open', true] );

        array_push($atr, ['user_id', auth()->user()->id] );

        $caja = Caja::where($atr)->get();
        if ( count($caja) !=0 ) {
            return true;
        }
        return false;
    }
}
