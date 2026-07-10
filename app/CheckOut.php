<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    protected $table = "checkout";
    
    public function tarifa(){
        return $this->belongsTo(TarifaExtra::class, 'tarifa_id');
    }
}
