<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'title', 'message', 'status'];

    protected $touches = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function inquiryReply()
    {
        return $this->hasOne(InquiryReply::class, 'inquiry_id', 'id');
    }
}
