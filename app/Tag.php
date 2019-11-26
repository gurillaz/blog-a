<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    public function articles(){

        return $this->belongsToMany(Article::class);
        
    }
    public function user(){

        return $this->belongsTo(User::class);
        
    }

}
