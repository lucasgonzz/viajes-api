<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $dates = ['to_execute_at'];

    function scopeWithAll($query) {
        $query->with('order_operation', 'order_type', 'order_status', 'client', 'origin_client_address', 'destination_client_address', 'driver', 'location', 'packages');
    }

    function order_operation() {
        return $this->belongsTo('App\Models\OrderOperation');
    }

    function order_type() {
        return $this->belongsTo('App\Models\OrderType');
    }

    function order_status() {
        return $this->belongsTo('App\Models\OrderStatus');
    }

    function client() {
        return $this->belongsTo('App\Models\Client');
    }

    function origin_client_address() {
        return $this->belongsTo('App\Models\ClientAddress', 'origin_client_address_id');
    }

    function destination_client_address() {
        return $this->belongsTo('App\Models\ClientAddress', 'destination_client_address_id');
    }

    function driver() {
        return $this->belongsTo('App\Models\Driver');
    }

    function location() {
        return $this->belongsTo('App\Models\Location');
    }

    function packages() {
        return $this->belongsToMany('App\Models\Package')->withPivot('amount', 'price');
    }
}
