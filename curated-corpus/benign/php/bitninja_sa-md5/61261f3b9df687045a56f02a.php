<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mt4details extends Model
{

    public function tuser(){
    	return $this->belongsTo('App\User', 'client_id');
    }
}
