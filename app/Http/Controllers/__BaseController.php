<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MODEL_NAME;

class MODEL_NAMEController extends Controller
{

    MODEL_NAME

    public function index() {
        $models = MODEL_NAME::orderBy('name', 'ASC')
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = MODEL_NAME::create([
            'name'      => $request->name,
        ]);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = MODEL_NAME::find($id);
        $model->name       = $request->name;
        $model->save();
        return response()->json(['model' => $this->getFullModel('App\Models\MODEL_NAME', $model->id)], 200);
    }

    function updateImage(Request $request, $id) {
        $model = MODEL_NAME::find($id);
        if (!is_null($model->image_url)) {
            ImageHelper::deleteImage($model->image_url);
        }
        $model->image_url = $request->image_url;
        $model->save();
        return response()->json(['model' => $model], 200); 
    }

    public function destroy($id) {
        $model = MODEL_NAME::find($id);
        if (!is_null($model->image_url)) {
            $res = ImageHelper::deleteImage($model->image_url);
        }
        $model->delete();
        return response(null, 200);
    }
}
