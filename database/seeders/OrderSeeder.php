<?php

namespace Database\Seeders;

use App\Http\Controllers\Helpers\CurrentAcountHelper;
use App\Models\Location;
use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            [
                'num'                               => 1,
                'order_operation_id'                => 1,
                'order_type_id'                     => 1,
                'order_status_id'                   => 1,
                'client_id'                         => 1,
                'origin_client_address_id'          => 1,
                'destination_client_address_id'     => 2,
                'observations'                      => 'Esta es una observacion',
                'driver_id'                         => 1,
                'location_id'                       => 1,
                'created_at'                        => Carbon::now()->subDays(2),
                'to_execute_at'                     => Carbon::today()->subDays(1),
                'to_limit_execute_at'               => Carbon::today()->addDays(1),
                'packages'                          => [
                                                        [
                                                            'name'      => 'Caja chica',
                                                            'amount'    => 3
                                                        ]
                                                    ],
            ],
            [
                'num'                               => 2,
                'order_operation_id'                => 2,
                'order_type_id'                     => 2,
                'order_status_id'                   => 2,
                'client_id'                         => 2,
                'origin_client_address_id'          => 3,
                'destination_client_address_id'     => 4,
                'observations'                      => '',
                'driver_id'                         => 2,
                'location_id'                       => 2,
                'money_price'                       => 300,
                'created_at'                        => Carbon::now(),
                'to_execute_at'                     => Carbon::today()->addDays(1),
                'to_limit_execute_at'               => null,
            ],
            [
                'num'                               => 3,
                'order_operation_id'                => 2,
                'order_type_id'                     => 3,
                'order_status_id'                   => 2,
                'client_id'                         => 2,
                'origin_client_address_id'          => 3,
                'destination_client_address_id'     => 4,
                'observations'                      => '',
                'driver_id'                         => 2,
                'location_id'                       => 2,
                'person_price'                      => 200,
                'person_amount'                     => 2,
                'created_at'                        => Carbon::now()->subDays(2),
                'to_execute_at'                     => Carbon::today(),
                'to_limit_execute_at'               => null,
            ],
        ];
        for ($i=30; $i >= -2; $i--) { 
            foreach ($models as $model) {
                $order = Order::create([
                    'num'                               => $model['num'],
                    'order_operation_id'                => $model['order_operation_id'],
                    'order_type_id'                     => $model['order_type_id'],
                    'order_status_id'                   => $model['order_status_id'],
                    'client_id'                         => $model['client_id'],
                    'origin_client_address_id'          => $model['origin_client_address_id'],
                    'destination_client_address_id'     => $model['destination_client_address_id'],
                    'observations'                      => $model['observations'],
                    'driver_id'                         => $model['driver_id'],
                    'location_id'                       => $model['location_id'],
                    'to_execute_at'                     => $model['to_execute_at'],
                    'to_limit_execute_at'               => $model['to_limit_execute_at'],
                    'created_at'                        => $model['created_at'],
                    'person_price'                      => isset($model['person_price']) ? $model['person_price'] : null,
                    'person_amount'                     => isset($model['person_amount']) ? $model['person_amount'] : null,
                    'money_price'                       => isset($model['money_price']) ? $model['money_price'] : null,
                    'user_id'                           => 1,
                ]);
                $this->attachPackages($order, $model);
                CurrentAcountHelper::createCurrentAcount($order);
            }
        }
    }

    function attachPackages($order, $model) {
        if (isset($model['packages'])) {
            $location = Location::find($order->location_id);
            $packages_to_attach = [];
            foreach ($model['packages'] as $package) {
                $packages_to_add = Package::where('name', $package['name'])->first();
                $packages_to_attach[] = [
                    'id'        => $packages_to_add->id,
                    'price'     => $this->packagePrice($packages_to_add, $order->location_id),
                    'amount'    => $package['amount'],
                ];
            }
            foreach ($packages_to_attach as $package) {
                $order->packages()->attach($package['id'], [
                                                'price'     => $package['price'],
                                                'amount'    => $package['amount'],
                                            ]);
            }
        }
    }

    function packagePrice($package, $location_id) {
        foreach ($package->locations as $location) {
            if ($location->id == $location_id) {
                return $location->pivot->price;
            }
        }
    }
}
