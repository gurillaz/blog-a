<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public function articles(){

        return $this->hasMany(Article::class,'category_id');
        
    }
    public function user(){

        return $this->belongsTo(User::class,'user_id');
        
    }

}
