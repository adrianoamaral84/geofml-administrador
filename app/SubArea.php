<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubArea extends Model
{
    protected $table = "subarea";

   	public $timestamps = false;

    protected $fillable = [
        'descricao',
    ];
    protected $hidden = [
        'updated_at', 'created_at',
    ];
    

    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public static function listAll($search, $id){
        //$search = "admin";
        //dd($id);
        if ($search == null) {           
            $area = \App\Area::find($id);
            return $area->subarea()->paginate(10);
        } else {
            //return 'nao null';
            $area = \App\Area::find($id);
            return $area->subarea()->where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }
    
}
