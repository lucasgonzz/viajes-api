<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index() {
        $models = Client::where('user_id', $this->userId())
                            ->orderBy('name', 'ASC')
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = Client::create([
            'name'      => $this->fU($request->name),
            'address'   => $request->address,
            'user_id'   => $this->userId(),
        ]);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = Client::find($id);
        $model->name       = $this->fU($request->name);
        $model->address    = $request->address;
        $model->save();
        return response()->json(['model' => $model], 200);
    }

    function updateImage(Request $request, $id) {
        $model = Client::find($id);
        if (!is_null($model->image_url)) {
            ImageHelper::deleteImage($model->image_url);
        }
        $model->image_url = $request->image_url;
        $model->save();
        return response()->json(['model' => $model], 200); 
    }

    public function destroy($id) {
        $model = Client::find($id);
        if (!is_null($model->image_url)) {
            $res = ImageHelper::deleteImage($model->image_url);
        }
        $model->delete();
        return response(null, 200);
    }
}
