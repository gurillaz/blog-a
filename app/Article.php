<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Article extends Model
{

    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty  = true;

    public function category(){

        return $this->belongsTo(Category::class,'category_id');
        
    }
    public function user(){

        return $this->belongsTo(User::class,'user_id');
        
    }
    public function comments(){

        return $this->morphMany(Comment::class,'commentable');
        
    }    
    
    public function tags(){

        return $this->belongsToMany(Tag::class);
        
    }
}
