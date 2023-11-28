<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageFeedbackReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['package_feedback_id', 'user_id', 'reply'];

    protected $touches = ['user', 'packageFeedback'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function packageFeedback()
    {
        return $this->belongsTo(PackageFeedback::class, 'package_feedback_id', 'id');
    }
}
