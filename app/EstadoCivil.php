<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
	public $timestamps = false;
	
    protected $fillable = [
        'descricao',
    ];
    public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return EstadoCivil::paginate(10);
        } else {
            //return 'nao null';
            return EstadoCivil::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
}
