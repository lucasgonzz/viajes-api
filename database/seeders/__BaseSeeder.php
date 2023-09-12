<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class __BaseSeeder extends Seeder
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
                'name' => '',
            ],
        ];
        foreach ($models as $model) {
            MODEL_NAME::create([
                'name' => $model['name']
            ]);
        }
    }
}
