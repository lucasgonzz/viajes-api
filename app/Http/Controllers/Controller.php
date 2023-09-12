<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function userId() {
        $user = Auth()->user();
        if (!is_null($user)) {
            if ($user->type == 'admin') {
                return $user->id;
            } else if ($user->type == 'driver') {
                return $user->admin_id;
            }
        }
        return null;
    }

    function fU($string) {
        return ucfirst(strtolower($string));
    }

    function getFullModel($model_name, $id) {
        $model = $model_name::where('id', $id)
                        ->withAll()
                        ->first();
        return $model;
    }

    function num($table) {
        $last = DB::table($table)
                    ->where('user_id', $this->userId())
                    ->orderBy('id', 'DESC')
                    ->first();
        if (is_null($last) || is_null($last->num)) {
            return 1;
        }
        return $last->num + 1;
    }
}
