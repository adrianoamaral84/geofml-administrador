<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = "area";
    public $timestamps = false;
    
    protected $fillable = [
        'descricao',
    ];
    protected $hidden = [
        'updated_at', 'created_at',
    ];
    
    public static function listAll($search){
        //$search = "admin";
        //dd($search);
        if ($search == null) {           
            return Area::paginate(10);
        } else {
            //return 'nao null';
            return Area::where('descricao', 'LiKE', '%'.$search.'%')->paginate(10);
        }
    }

    public function subarea()
    {
    //return $this->belongsTo(User::class, 'campo_id');
    return $this->hasMany(SubArea::class, 'area_id');
    }
    
    /*
    public static function listAll() 
    {
        return Area::all();
    }
    */


}
