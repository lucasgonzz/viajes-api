<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
                'name'          => 'Lisandro',
                'doc_number'    => 123,
                'password'      => '1234',
                'company_name'  => 'Lisandro viajes',
                'image_url'     => 'v1653571722/articles/ibqe6ejo529nslxsjlxv.jpg',
                'type'          => 'admin',
            ],
            [
                'name'          => 'Lisandro',
                'doc_number'    => 1234,
                'password'      => '1234',
                'company_name'  => 'Lisandro viajes',
                'image_url'     => 'v1653571722/articles/ibqe6ejo529nslxsjlxv.jpg',
                'type'          => 'driver',
            ],
        ];
        foreach ($models as $model) {
            $user = User::create([
                'name'          => $model['name'],
                'doc_number'    => $model['doc_number'],
                'password'      => bcrypt($model['password']),
                'company_name'  => $model['company_name'],
                'image_url'     => $model['image_url'],
                'type'          => $model['type'],
                'admin_id'      => $model['type'] == 'driver' ? 1 : null,
            ]);
        }
    }
}
