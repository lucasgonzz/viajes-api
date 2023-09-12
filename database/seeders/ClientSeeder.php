<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
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
                'name' => 'Lucas Gonzalez',
                'address' => 'Carmen Gadea 787',
            ],
            [
                'name' => 'Marcos Juares',
                'address' => 'San antonio 222',
            ],
        ];
        for ($i=1; $i < 7; $i++) { 
            foreach ($models as $model) {
                Client::create([
                    'name'      => $model['name'].' '.$i,
                    'address'   => $model['address'],
                    'user_id'   => 1,
                ]);
            }
        }
    }
}
