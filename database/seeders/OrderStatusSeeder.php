<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
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
                'name' => 'Sin Entregar',
            ],
            [
                'name' => 'Entregado',
            ],
            [
                'name' => 'No se pudo entregar',
            ],
        ];
        foreach ($models as $model) {
            OrderStatus::create([
                'name' => $model['name']
            ]);
        }
    }
}
