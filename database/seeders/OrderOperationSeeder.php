<?php

namespace Database\Seeders;

use App\Models\OrderOperation;
use Illuminate\Database\Seeder;

class OrderOperationSeeder extends Seeder
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
                'name' => 'Entrega',
            ],
            [
                'name' => 'Retiro',
            ],
        ];
        foreach ($models as $model) {
            OrderOperation::create([
                'name' => $model['name']
            ]);
        }
    }
}
