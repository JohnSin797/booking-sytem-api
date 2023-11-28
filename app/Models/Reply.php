<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['reply_to', 'user_id', 'comment'];

    protected $touches = ['user', 'feedback'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'reply_to', 'id');
    }
}
