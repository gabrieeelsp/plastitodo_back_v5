<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests\v1\clients\UpdateClientRequest;

use App\Http\Resources\v1\clients\ClientResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchText = trim($request->get('q'));
        $val = explode(' ', $searchText );

        $atr_surname = [];
        $atr_name = [];

        foreach ($val as $q) {
            array_push($atr_name, ['name', 'LIKE', '%'.strtolower($q).'%'] );
            array_push($atr_surname, ['surname', 'LIKE', '%'.strtolower($q).'%'] );
            
        };

        if ( $request->has('ivacondition_id') ) {
            array_push($atr_name, ['ivacondition_id', '=', $request->get('ivacondition_id')] );
        }

        if ( $request->has('tipo') ) {
            array_push($atr_name, ['tipo', '=', $request->get('tipo')] );
        }

        $limit = 5;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }
        
        $users = null;
        //Paginate?
        if ( $request->has('paginate')) {
            $paginate = $request->get('paginate');
            
            if ( $paginate == 0 ) { 
                $users = User::orderBy('name', 'ASC')
                    ->where($atr_name)
                    ->orWhere($atr_surname)->get();
                    
                    return ClientResource::collection($users);

            }
            
        }
        
        
        $users = User::orderBy('name', 'ASC')
            ->orWhere($atr_name)
            ->paginate($limit);       
        
        return ClientResource::collection($users);
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($client_id)
    {
        $client = User::findOrFail($client_id);
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        //return $request->all();

        $user = User::findOrFail($user_id);

        //return $request->input('data.attributes');
        $user->update($request->input('data.attributes'));

        if ( $request->has('data.relationships.ivacondition')) {
            $user->ivacondition_id = $request->get('data')['relationships']['ivacondition']['id'];
        }

        if ( $request->has('data.relationships.doctype')) {
            $user->doctype_id = $request->get('data')['relationships']['doctype']['id'];
        }

        $user->save();

        return new ClientResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
