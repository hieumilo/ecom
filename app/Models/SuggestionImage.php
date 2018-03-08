<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuggestionImage extends Model
{
    protected $guarded = [];

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }

    public function setImageAttribute($value)
    {
        if (!$value) {
            $this->attributes['image'] = config('settings.path_image') . 'product/default.png';
        } else {
            $this->attributes['image'] = $value;
        }
    }
}
