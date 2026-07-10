<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TituloDiploma extends Model
{
   public $timestamps = false;
   public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return TituloDiploma::paginate(10);
        } else {
            //return 'nao null';
            return TituloDiploma::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
