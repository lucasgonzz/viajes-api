<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CurrentAcountHelper;
use App\Http\Controllers\Helpers\Pdf\CurrentAcountPdf;
use App\Models\CurrentAcount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CurrentAcountController extends Controller
{
    function index($client_id, $months_ago) {
        $models = CurrentAcount::where('client_id', $client_id)
                            ->whereDate('created_at', '>=', $months_ago)
                            ->orderBy('created_at', 'ASC')
                            ->with(['order' => function($q) {
                                return $q->withAll();
                            }])
                            ->with('checks')
                            ->with('current_acount_payment_method')
                            ->get();
        return response()->json(['models' => $models], 200);
    }

    function pago(Request $request) {
        $model = CurrentAcount::create([
            'client_id'                         => $request->client_id,
            'haber'                             => $request->haber,
            'description'                       => $request->description,
            'status'                            => 'pago_from_client',
            'current_acount_payment_method_id'  => $request->current_acount_payment_method_id,
            'created_at'                        => $request->current_date ? Carbon::now() : $request->created_at,
        ]);
        $model->num_receipt = CurrentAcountHelper::getNumReceipt($model);
        $model->saldo = CurrentAcountHelper::getSaldo($model) - (float)$model->haber;
        $model->save();
        CurrentAcountHelper::saveChecks($model, $request->checks);
        if (!$request->current_date) {
            CurrentAcountHelper::updateSaldos($model);
        }
    }

    function notaCredito(Request $request) {
        $model = CurrentAcount::create([
            'client_id'                         => $request->client_id,
            'haber'                             => $request->haber,
            'description'                       => $request->description,
            'status'                            => 'nota_credito',
            'created_at'                        => $request->current_date ? Carbon::now() : $request->created_at,
        ]);
        $model->num_receipt = CurrentAcountHelper::getNumReceipt($model);
        $model->saldo = CurrentAcountHelper::getSaldo($model) - (float)$model->haber;
        $model->save();
        if (!$request->current_date) {
            CurrentAcountHelper::updateSaldos($model);
        }
    }

    function saldoInicial(Request $request) {
        $model = CurrentAcount::create([
            'client_id'                         => $request->client_id,
            'haber'                             => $request->is_for_debe ? null : $request->saldo_inicial,
            'debe'                              => $request->is_for_debe ? $request->saldo_inicial : null,
            'saldo'                             => $request->is_for_debe ? $request->saldo_inicial : floatval(-$request->saldo_inicial),
            'description'                       => $request->description,
            'status'                            => 'saldo_inicial',
        ]);
    }

    function delete($id) {
        CurrentAcountHelper::delete($id);
        return response(null, 200);
    }

    function pdf($client_id, $months_ago) {
        $models = CurrentAcount::where('client_id', $client_id)
                            ->whereDate('created_at', '>=', $months_ago)
                            ->orderBy('created_at', 'ASC')
                            ->get();

        $pdf = new CurrentAcountPdf($models);
    }
}
