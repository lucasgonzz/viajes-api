<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
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
                'name'          => 'Juan Perez',
                'address'       => 'Palacios 223',
                'phone'         => '3444447721',
                'observations'  => 'Vive a la vuelta de la casa de la madre',
            ],
            [
                'name'          => 'Micaela Guzman',
                'address'       => '25 de Mayo 1800',
                'phone'         => '3444447721',
                'observations'  => '',
            ],
        ];
        foreach ($models as $model) {
            Driver::create([
                'name'          => $model['name'],
                'address'       => $model['address'],
                'phone'         => $model['phone'],
                'observations'  => $model['observations'],
                'user_id'       => 1,
            ]);
        }
    }
}
