<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CurrentAcountHelper;
use App\Http\Controllers\Helpers\OrderHelper;
use App\Http\Controllers\Helpers\Pdf\OrderPdf;
use App\Models\CurrentAcount;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function index($view) {
        $models = Order::where('user_id', $this->userId())
                        ->withAll()
                        ->orderBy('created_at', 'DESC');
        if ($view == 'created-today') {
            $models = $models->where('created_at', '>=', Carbon::today())
                                ->where('created_at', '<', Carbon::today()->addDay());
        } else if ($view == 'executed-today') {
            $models = $models->whereDate('to_execute_at', Carbon::today())
                                ->where('to_execute_at', '<', Carbon::today()->addDay())
                                ->orWhere(function($query) {
                                    $query->where('to_limit_execute_at', '>=', Carbon::today());
                                });
        } else if ($view == 'executed-tomorrow') {
            $models = $models->where('to_execute_at', '>=', Carbon::today()->addDay())
                                ->where('to_execute_at', '<', Carbon::today()->addDays(2))
                                ->orWhere(function($query) {
                                    $query->where('to_limit_execute_at', '>=', Carbon::today());
                                });
        }
        $models = $models->get();
        return response()->json(['models' => $models], 200);
    }

    public function store(Request $request) {
        $model = Order::create([
            'num'                               => $this->num('orders'),
            'order_operation_id'                => $request->order_operation_id,
            'client_id'                         => $request->client_id,
            'origin_client_address_id'          => $request->origin_client_address_id,
            'destination_client_address_id'     => $request->destination_client_address_id,
            'with_money'                        => $request->with_money,
            'order_status_id'                   => $request->order_status_id,
            'order_type_id'                     => $request->order_type_id,
            'driver_id'                         => $request->driver_id,
            'location_id'                       => $request->location_id,
            'money_price'                       => $request->money_price,
            'person_price'                      => $request->person_price,
            'person_amount'                     => $request->person_amount,
            'to_execute_at'                     => $request->to_execute_at,
            'to_limit_execute_at'               => $request->to_limit_execute_at,
            'observations'                      => $request->observations,
            'user_id'                           => $this->userId(),
        ]);
        OrderHelper::attachPackages($model, $request->packages);
        CurrentAcountHelper::createCurrentAcount($model);
        return response()->json(['model' => $this->getFullModel('App\Models\Order', $model->id)], 201);
    }

   
    public function update(Request $request, $id) {
        $model = Order::find($id);
        Log::info('id: '.$id);
        $model->order_operation_id                  = $request->order_operation_id;
        $model->client_id                           = $request->client_id;
        $model->origin_client_address_id            = $request->origin_client_address_id;
        $model->destination_client_address_id       = $request->destination_client_address_id;
        $model->with_money                          = $request->with_money;
        $model->order_status_id                     = $request->order_status_id;
        $model->order_type_id                       = $request->order_type_id;
        $model->driver_id                           = $request->driver_id;
        $model->location_id                         = $request->location_id;
        $model->money_price                         = $request->money_price;
        $model->person_price                        = $request->person_price;
        $model->person_amount                       = $request->person_amount;
        $model->to_execute_at                       = $request->to_execute_at;
        $model->to_limit_execute_at                 = $request->to_limit_execute_at;
        $model->observations                        = $request->observations;
        $model->save();
        OrderHelper::attachPackages($model, $request->packages);
        CurrentAcountHelper::updateCurrentAcount($model);
        return response()->json(['model' => $this->getFullModel('App\Models\Order', $model->id)], 200);
    }

    public function destroy($id) {
        $model = Order::find($id);
        CurrentAcountHelper::delete($model->current_acount);
        $model->delete();
        return response(null, 200);
    }

    function pdf($id) {
        $model = Order::find($id);
        $pdf = new OrderPdf($model);
    }

    function filter(Request $request) {
        $models = Order::where('user_id', $this->userId());
        if (isset($request->order_operation_id)) {
            $models = $models->where('order_operation_id', $request->order_operation_id);
        }
        if (isset($request->client_id)) {
            $models = $models->where('client_id', $request->client_id);
        }
        if (isset($request->origin_client_address_id)) {
            $models = $models->where('origin_client_address_id', $request->origin_client_address_id);
        }
        if (isset($request->destination_client_address_id)) {
            $models = $models->where('destination_client_address_id', $request->destination_client_address_id);
        }
        if (isset($request->with_money)) {
            $models = $models->where('with_money', $request->with_money);
        }
        if (isset($request->order_status_id)) {
            $models = $models->where('order_status_id', $request->order_status_id);
        }
        if (isset($request->driver_id)) {
            $models = $models->where('driver_id', $request->driver_id);
        }
        if (isset($request->location_id)) {
            $models = $models->where('location_id', $request->location_id);
        }
        if (isset($request->order_type_id)) {
            $models = $models->where('order_type_id', $request->order_type_id);
        }
        if (isset($request->created_at)) {
            $models = $models->whereDate('created_at', $request->created_at);
        }
        if (isset($request->to_execute_at)) {
            $models = $models->whereDate('to_execute_at', $request->to_execute_at);

            $models = $models->where('to_execute_at', '>=', $request->to_execute_at)
                            ->where('to_execute_at', '<', Carbon::parse($request->to_execute_at)->addDay())
                            ->orWhere(function($query) use($request) {
                                $query->where('to_limit_execute_at', '>=', $request->to_execute_at);
                            });
        }
        // if (isset($request->to_limit_execute_at)) {
        //     $models = $models->whereDate('to_limit_execute_at', $request->to_limit_execute_at);
        // }
        $models = $models->pluck('id');
        $results = [];
        foreach ($models as $model) {
            $results[] = $this->getFullModel('App\Models\Order', $model);
        }
        return response()->json(['models' => $results], 200);
    }
}
