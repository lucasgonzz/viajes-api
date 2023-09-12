<?php

namespace App\Http\Controllers;

use App\Models\CurrentAcountPaymentMethod;
use Illuminate\Http\Request;

class CurrentAcountPaymentMethodController extends Controller
{
    function index() {
        $models = CurrentAcountPaymentMethod::all();
        return response()->json(['models' => $models], 200);
    }
}
