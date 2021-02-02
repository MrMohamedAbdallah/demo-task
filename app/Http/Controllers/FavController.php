<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    
    public function index(){
        $favs = Auth::user()->favs()->get();
        
        return view('home')->with('favs', $favs);
    }
}
