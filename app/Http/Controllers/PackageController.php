<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\PackageHelper;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    public function index() {
        $models = Package::where('user_id', $this->userId())
                            ->orderBy('name', 'ASC')   
                            ->withAll()
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = Package::create([
            'name'      => $request->name,
            'user_id'   => $this->userId(),
        ]);
        PackageHelper::attachLocations($model);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = Package::find($id);
        $model->name       = $request->name;
        $model->save();
        return response()->json(['model' => $this->getFullModel('App\Models\Package', $model->id)], 200);
    }

    function updateImage(Request $request, $id) {
        $model = Package::find($id);
        if (!is_null($model->image_url)) {
            ImageHelper::deleteImage($model->image_url);
        }
        $model->image_url = $request->image_url;
        $model->save();
        return response()->json(['model' => $model], 200); 
    }

    public function destroy($id) {
        $model = Package::find($id);
        if (!is_null($model->image_url)) {
            $res = ImageHelper::deleteImage($model->image_url);
        }
        $model->delete();
        return response(null, 200);
    }
}
