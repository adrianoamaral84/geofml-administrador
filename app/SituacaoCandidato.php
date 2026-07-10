<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituacaoCandidato extends Model
{
   public $timestamps = false;
   public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return SituacaoCandidato::paginate(10);
        } else {
            //return 'nao null';
            return SituacaoCandidato::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
