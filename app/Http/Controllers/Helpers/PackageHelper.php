<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Location;

class PackageHelper {
	
	static function attachLocations($package) {
		$locations = Location::where('user_id', $package->user_id)
								->get();
		foreach ($locations as $location) {
			$location->packages()->attach($package->id);
		}
	}

}
