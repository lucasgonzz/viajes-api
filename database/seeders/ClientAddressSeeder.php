<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientAddress;
use Illuminate\Database\Seeder;

class ClientAddressSeeder extends Seeder
{
    
    public function run()
    {
        $models = [
            [
                'name' => 'Fabrica Norte',
                'address' => 'Chacauco 1889',
                'observations' => 'Al lado de aeroparque',
            ],
            [
                'name' => 'Ramiro Marra',
                'address' => 'San Antonio 223',
                'observations' => '',
            ],
        ];
        $clients = Client::all();
        foreach ($clients as $client) {
            foreach ($models as $model) {
                ClientAddress::create([
                    'name'          => $model['name'].' / '.$client->name,
                    'address'       => $model['address'],
                    'observations'  => $model['observations'],
                    'client_id'     => $client->id,
                ]);
            }
        }
    }
}
