<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\LocationHelper;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function index() {
        $models = Location::where('user_id', $this->userId())  
                            ->orderBy('name', 'ASC')
                            ->withAll()
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = Location::create([
            'name'          => $this->fU($request->name),
            'person_price'  => $request->person_price,
            'money_price'   => $request->money_price,
            'user_id'       => $this->userId(),
        ]);
        $model = LocationHelper::attachPackages($model, $request->packages);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = Location::find($id);
        $model->name            = $this->fU($request->name);
        $model->person_price    = $request->person_price;
        $model->money_price     = $request->money_price;
        $model->save();
        $model = LocationHelper::attachPackages($model, $request->packages);
        return response()->json(['model' => $this->getFullModel('App\Models\Location', $model->id)], 200);
    }

    function updateImage(Request $request, $id) {
        $model = Location::find($id);
        if (!is_null($model->image_url)) {
            ImageHelper::deleteImage($model->image_url);
        }
        $model->image_url = $request->image_url;
        $model->save();
        return response()->json(['model' => $model], 200); 
    }

    public function destroy($id) {
        $model = Location::find($id);
        if (!is_null($model->image_url)) {
            $res = ImageHelper::deleteImage($model->image_url);
        }
        $model->delete();
        return response(null, 200);
    }
}
