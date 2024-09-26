<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::insert([
            ['cliente_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),],
            ['cliente_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),],
            ['cliente_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),],
            ['cliente_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),],
            ['cliente_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),],
        ]);
    }
}
