<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = [];
 
    function scopeWithAll($query) {
        $query->with('locations');
    }

    function locations() {
        return $this->belongsToMany('App\Models\Location')->withPivot('price');
    }
}
