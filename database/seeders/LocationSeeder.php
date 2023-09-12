<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Package;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->packages = Package::all();
        $models = [
            [
                'name' => 'Belgrano',
                'packages' => [
                    [
                        'name' => 'Caja chica',
                        'price' => 100
                    ],
                    [
                        'name' => 'Caja grande',
                        'price' => 150
                    ],
                ],
                'person_price' => 200,
                'money_price'  => 50,
            ],
            [
                'name' => 'Colegiales',
                'packages' => [
                    [
                        'name' => 'Caja chica',
                        'price' => 200
                    ],
                    [
                        'name' => 'Caja grande',
                        'price' => 250
                    ],
                ],
                'person_price' => 300,
                'money_price'  => 100,
            ],
            [
                'name' => 'Constitucion',
                'packages' => [
                    [
                        'name' => 'Caja chica',
                        'price' => 300
                    ],
                    [
                        'name' => 'Caja grande',
                        'price' => 350
                    ],
                ],
                'person_price' => 400,
                'money_price'  => 200,
            ],
        ];
        foreach ($models as $model) {
            $location = Location::create([
                'name'          => $model['name'],
                'person_price'  => $model['person_price'],
                'money_price'   => $model['money_price'],
                'user_id'       => 1,
            ]);
            $this->attachPackages($location, $model);
        }
    }

    function attachPackages($location, $model) {
        foreach ($this->packages as $package) {
            $pivot_package = [];
            foreach ($model['packages'] as $model_package) {
                if ($package->name == $model_package['name']) {
                    $pivot_package = [
                        'id'    => $package->id,
                        'price' => $model_package['price'],
                    ];
                }
            }
            $location->packages()->attach($pivot_package['id'], [
                                            'price' => $pivot_package['price']
                                        ]);
        }
    }
}
