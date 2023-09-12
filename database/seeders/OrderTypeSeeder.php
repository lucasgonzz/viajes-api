<?php

namespace Database\Seeders;

use App\Models\OrderType;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
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
                'name' => 'Bultos',
            ],
            [
                'name' => 'Plata',
            ],
            [
                'name' => 'Pasajeros',
            ],
        ];
        foreach ($models as $model) {
            OrderType::create([
                'name' => $model['name']
            ]);
        }
    }
}
