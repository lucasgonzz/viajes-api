<?php

namespace App\Http\Controllers;

use App\Models\OrderType;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    function index() {
        $models = OrderType::all();
        return response()->json(['models' => $models], 200);
    }
}
