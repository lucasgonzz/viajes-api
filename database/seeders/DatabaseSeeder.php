<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(OrderOperationSeeder::class);
        $this->call(OrderTypeSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ClientAddressSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(CurrentAcountPaymentMethodSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
