<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
   	public $timestamps = false;
   	public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return Curso::paginate(10);
        } else {
            //return 'nao null';
            return Curso::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
