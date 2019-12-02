<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{

    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty  = true;

    public function articles(){

        return $this->hasMany(Article::class,'category_id');
        
    }
    public function user(){

        return $this->belongsTo(User::class,'user_id');
        
    }

}
