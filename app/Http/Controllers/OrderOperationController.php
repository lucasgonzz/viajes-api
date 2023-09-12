<?php

namespace App\Http\Controllers;

use App\Models\OrderOperation;
use Illuminate\Http\Request;

class OrderOperationController extends Controller
{
    function index() {
        $models = OrderOperation::all();
        return response()->json(['models' => $models], 200);
    }
}
