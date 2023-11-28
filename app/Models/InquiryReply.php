<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InquiryReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['inquiry_id', 'message', 'status'];

    protected $touches = ['inquiry'];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id', 'id');
    }
}
