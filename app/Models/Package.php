<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'package_type',
        'product_type',
        'quantity',
        'total_price',
        'status',
        'image',
        'description',
        'capital',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($parent) {
            $parent->packageItem()->delete();
        });
    }

    public function packageItem()
    {
        return $this->hasMany(PackageItem::class, 'package_id', 'id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'package_id', 'id');
    }

    public function packageFeedbacks()
    {
        return $this->hasMany(PackageFeedback::class, 'package_id', 'id');
    }

    public function rating()
    {
        return $this->hasMany(PackageRating::class, 'package_id', 'id');
    }
}
