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
        // Chama os seeders necessários
        $this->call([
            UsersTableSeeder::class,
            ClientsTableSeeder::class,
            ProductsTableSeeder::class,
            OrdersTableSeeder::class,
            OrderProductTableSeeder::class,
        ]);
    }
}
