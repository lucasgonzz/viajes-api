<?php

namespace App\Http\Controllers\Helpers;

class BaseHelper {
        
    function getFullModel($model_name, $id) {
        $model = $model_name::where('id', $id)
                        ->withAll()
                        ->first();
        return $model;
    }
}
