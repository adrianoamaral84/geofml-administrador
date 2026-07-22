<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    
    protected $table = "status"; 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function usuarios()
{
    return $this->hasMany(User::class, 'status', 'id');
}
}
