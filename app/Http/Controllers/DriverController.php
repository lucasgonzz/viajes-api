<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    public function index() {
        $models = Driver::where('user_id', $this->userId())
                            ->orderBy('name', 'ASC')
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = Driver::create([
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'observations'  => $request->observations,
            'user_id'       => $this->userId(),
        ]);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = Driver::find($id);
        $model->name            = $request->name;
        $model->address         = $request->address;
        $model->phone           = $request->phone;
        $model->observations    = $request->observations;
        $model->save();
        return response()->json(['model' => $model], 200);
    }

    function updateImage(Request $request, $id) {
        $model = Driver::find($id);
        if (!is_null($model->image_url)) {
            ImageHelper::deleteImage($model->image_url);
        }
        $model->image_url = $request->image_url;
        $model->save();
        return response()->json(['model' => $model], 200); 
    }

    public function destroy($id) {
        $model = Driver::find($id);
        if (!is_null($model->image_url)) {
            $res = ImageHelper::deleteImage($model->image_url);
        }
        $model->delete();
        return response(null, 200);
    }
}
