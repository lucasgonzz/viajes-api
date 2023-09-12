<?php

namespace App\Http\Controllers;

use App\Models\ClientAddress;
use Illuminate\Http\Request;

class ClientAddressController extends Controller
{

    public function index() {
        $models = ClientAddress::orderBy('name', 'ASC')
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = ClientAddress::create([
            'name'          => $request->name,
            'address'       => $request->address,
            'observations'  => $request->observations,
            'client_id'     => $request->client_id,
        ]);
        return response()->json(['model' => $model], 201);
    }

   
    public function update(Request $request, $id) {
        $model = ClientAddress::find($id);
        $model->name          = $request->name;
        $model->address       = $request->address;
        $model->observations  = $request->observations;
        $model->save();
        return response()->json(['model' => $model], 200);
    }

    public function destroy($id) {
        $model = ClientAddress::find($id);
        $model->delete();
        return response(null, 200);
    }
}
