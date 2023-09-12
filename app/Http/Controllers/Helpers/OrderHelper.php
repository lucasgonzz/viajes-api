<?php

namespace App\Http\Controllers\Helpers;

class OrderHelper {
	static function attachPackages($model, $packages) {
		if (!is_null($model->order_type) && $model->order_type->name == 'Bultos') {
			$model->packages()->detach();
			foreach ($packages as $package) {
				if (isset($package['pivot']['amount']) && $package['pivot']['amount'] != '') {
					$model->packages()->attach($package['id'], [
												'price'  => $package['pivot']['price'],
												'amount' => $package['pivot']['amount'],
											]);
				}
			}
		}
	}

	static function getTotalOrder($order) {
		$total = 0;
		if (!is_null($order->order_type)) {
			if ($order->order_type->name == 'Bultos') {
				foreach ($order->packages as $package) {
					$total += $package->pivot->price * $package->pivot->amount;
				}
			} else if ($order->order_type->name == 'Plata') {
				$total = $order->money_price;
			} else if ($order->order_type->name == 'Pasajeros') {
				$total = $order->person_price * $order->person_amount;
			}
		}
		return $total;
	}
}
