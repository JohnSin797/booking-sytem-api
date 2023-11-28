<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['package_id', 'user_id', 'comment'];

    protected $touches = ['user', 'package'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function packageFeedbackReply()
    {
        return $this->hasMany(PackageFeedbackReply::class, 'package_feedback_id', 'id');
    }
}
