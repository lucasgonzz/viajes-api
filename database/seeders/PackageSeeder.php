<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
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
                'name' => 'Caja chica',
            ],
            [
                'name' => 'Caja grande',
            ],
        ];
        foreach ($models as $model) {
            Package::create([
                'name'      => $model['name'],
                'user_id'   => 1,
            ]);
        }
    }
}
