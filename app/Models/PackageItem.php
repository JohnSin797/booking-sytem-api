<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id',
        'product_id'
    ];

    protected $touches = ['package', 'product'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
