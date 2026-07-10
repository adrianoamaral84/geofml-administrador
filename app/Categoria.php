<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
     public $timestamps = false;

     public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return Categoria::paginate(10);
        } else {
            //return 'nao null';
            return Categoria::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
