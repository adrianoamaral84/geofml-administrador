<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPublicacao extends Model
{
    public $timestamps = false;
   	public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return TipoPublicacao::paginate(10);
        } else {
            //return 'nao null';
            return TipoPublicacao::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
