<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Helpers\BaseHelper;

class LocationHelper {
        
	static function attachPackages($model, $packages) {
		$model->packages()->detach();
		foreach ($packages as $package) {
			$model->packages()->attach($package['id'], [
										'price' => Self::getPrice($package)
									]);			
		}
		return BaseHelper::getFullModel('App\Models\Location', $model->id);
	}

	static function getPrice($package) {
		if (isset($package['pivot']['price'])) {
			return $package['pivot']['price'];
		}
		return null;
	}

}
