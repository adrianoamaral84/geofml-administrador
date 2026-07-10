<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proficiencia extends Model
{
    public $timestamps = false;
   	public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return Proficiencia::paginate(10);
        } else {
            //return 'nao null';
            return Proficiencia::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
