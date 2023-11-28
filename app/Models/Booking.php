<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id',
        'user_id',
        'order_date',
        'status',
        'message',
        'location',
        'transaction_number',
        'services'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
