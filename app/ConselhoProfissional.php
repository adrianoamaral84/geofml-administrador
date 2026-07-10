<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConselhoProfissional extends Model
{
   public $timestamps = false;
   public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return ConselhoProfissional::paginate(10);
        } else {
            //return 'nao null';
            return ConselhoProfissional::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
