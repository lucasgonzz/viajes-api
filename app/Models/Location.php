<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    function scopeWithAll($query) {
        $query->with('packages');
    }

    function packages() {
        return $this->belongsToMany('App\Models\Package')->withPivot('price');
    }
}
