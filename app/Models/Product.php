<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'product_type', 'price', 'quantity', 'status', 'description', 'image', 'capital'
    ];

    // public function package()
    // {
    //     return $this->hasMany(Package::class, 'product_id', 'id')->onDelete(function ($package) {
    //         if (config('database.soft_delete')) {
    //             $package->delete();
    //         } else {
    //             $package->forceDelete();
    //         }
    //     });
    // }

    public function packageItem()
    {
        return $this->hasOne(PackageItem::class, 'product_id', 'id');
    }
}
