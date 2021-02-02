<?php

namespace App\Http\Controllers;

use App\Fav;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    
    public function index(){
        $favs = Auth::user()->favs()->get();
        
        return view('home')->with('favs', $favs);
    }

    /**
     * Togge fav_status for favorites
     */
    public function toggle(Request $request){
        $request->validate([
            'fav_id'    => 'required|numeric'
        ]);

        try{

            $fav = Fav::findOrFail($request->fav_id);
            
            if(Auth::user()->id != $fav->user_id){
                return reponse()->json([
                    'success'   => false,
                    'error'     => 'Forbidden'
                ],403);
            }

            $fav->fav_status = !$fav->fav_status;
            $fav->save();
            
            return response()->json([
                'success'   => true,
                'status'    => $fav->fav_status
            ],202);

        } catch(ModelNotFoundException $e){
            return response()->json([
                'success'   => false,
                'error'     => 'Record not found'
            ], 404);
        }
    }
}
