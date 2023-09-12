<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function update(Request $request) {
        $model = User::find($request->id);
        $model->doc_number = $request->doc_number;
        $model->company_name = $request->company_name;
        $model->save();
        return response()->json(['user' => $model], 200);
    }

    function updatePassword(Request $request) {
        if (Hash::check($request->current_password, Auth()->user()->password)) {
            $user = User::find(Auth()->user()->id);
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
            return response()->json(['updated' => true], 200);
        } else {
            return response()->json(['updated' => false], 200);
        }
    }
}
