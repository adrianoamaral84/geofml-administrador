<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoMilitar extends Model
{
    public $timestamps = false;
    public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return TipoDocumentoMilitar::paginate(10);
        } else {
            //return 'nao null';
            return TipoDocumentoMilitar::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
