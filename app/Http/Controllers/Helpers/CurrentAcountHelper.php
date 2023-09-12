<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Helpers\OrderHelper;
use App\Models\Check;
use App\Models\CurrentAcount;
use Illuminate\Support\Facades\Log;

class CurrentAcountHelper {

	static function createCurrentAcount($order) {
		if (!is_null($order->client_id) && OrderHelper::getTotalOrder($order) > 0) {
			$current_acount = CurrentAcount::create([
				'client_id' 	=> $order->client_id,
				'debe'      	=> OrderHelper::getTotalOrder($order),
				'order_id'		=> $order->id,
				'status'		=> 'sin_pagar',
				'created_at'	=> $order->created_at,
			]);
			$current_acount->saldo = Self::getSaldo($current_acount) + $current_acount->debe;
			$current_acount->save();
		}
	}

	static function updateCurrentAcount($order) {
		if (!is_null($order->client_id) && OrderHelper::getTotalOrder($order) > 0) {
			$current_acount = CurrentAcount::where('order_id', $order->id)	
											->first();
			$current_acount->debe =	OrderHelper::getTotalOrder($order);
			$current_acount->saldo = Self::getSaldo($current_acount) + OrderHelper::getTotalOrder($order);
			$current_acount->save();
			Self::updateSaldos($current_acount);
		}
	}

	static function updateSaldos($current_acount) {
		$following = CurrentAcount::where('client_id', $current_acount->client_id)
									->where('created_at', '>', $current_acount->created_at)
									->get();
		foreach ($following as $next) {
			if (!is_null($next->debe)) {
				$next->saldo = Self::getSaldo($next) + $next->debe;
			} else {
				$next->saldo = Self::getSaldo($next) - $next->haber;
			}
			$next->save();
		}
	}

	static function getNumReceipt($current_acount) {
		$last = CurrentAcount::where('created_at', '<', $current_acount->created_at)
							->orderBy('created_at', 'DESC')
							->first();
		if (is_null($last)) {
			return 1;
		}
		return $last->num_receipt + 1;
	}

	static function getSaldo($current_acount) {
		$last = CurrentAcount::where('client_id', $current_acount->client_id)
                            ->where('created_at', '<', $current_acount->created_at)
							->orderBy('created_at', 'DESC')
							->first();
		if (is_null($last)) {
			return 0;
		}
		return $last->saldo;
	}

    static function saveChecks($pago, $checks) {
        foreach ($checks as $check) {
        	if ($check['amount'] != '') {
	            Check::create([
	                'bank'                  => $check['bank'],
	                'payment_date'          => $check['payment_date'],
	                'amount'                => $check['amount'],
	                'num'                   => $check['num'],
	                'current_acount_id'     => $pago->id,
	            ]);
        	}
        }
    }

	static function delete($current_acount) {
		if (!is_null($current_acount)) {
			$current_acount->delete();
			Self::updateSaldos($current_acount);
		}
	}

}
