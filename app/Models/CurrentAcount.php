<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentAcount extends Model
{
    protected $guarded = [];

    function client() {
        return $this->belongsTo('App\Models\Client');
    }

    function order() {
        return $this->belongsTo('App\Models\Order');
    }

    function current_acount_payment_method() {
        return $this->belongsTo('App\Models\CurrentAcountPaymentMethod');
    }

    function checks() {
        return $this->hasMany('App\Models\Check');
    }
}
 