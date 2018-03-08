<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'image',
        'product_id',
    ];
    
    public function setImageAttribute($value)
    {
        if (!$value) {
            $this->attributes['image'] = config('settings.path_image') . 'product/default.png';
        } else {
            $this->attributes['image'] = $value;
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
