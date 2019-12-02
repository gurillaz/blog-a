<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty  = true;

    public function commentable()
    {

        return $this->morphTo();
    }
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function replies()
    {

        return $this->morphMany(Comment::class, 'commentable');
    }
}
