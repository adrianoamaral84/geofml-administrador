<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guarnicao extends Model
{
    protected $table = "guarnicao";
    
    public $timestamps = false;

    protected $fillable = [
        'descricao',
    ];

    public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return Guarnicao::paginate(10);
        } else {
            //return 'nao null';
            return Guarnicao::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
    public function uf()
    {
        return $this->belongsTo(Uf::class);
    }
}
