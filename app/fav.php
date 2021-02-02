<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    protected   $table = 'favs';

    /**
     * Return the user owner for the favorite
     */
    public function user(){
        return $this->belongsTo('users', 'user_id');
    }
}
