<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pedido_produto')->insert([
            ['pedido_id' => 1, 'produto_id' => 1, 'quantidade' => 2, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
            ['pedido_id' => 1, 'produto_id' => 2, 'quantidade' => 2, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
            ['pedido_id' => 2, 'produto_id' => 3, 'quantidade' => 2, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
            ['pedido_id' => 2, 'produto_id' => 4, 'quantidade' => 4, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
            ['pedido_id' => 3, 'produto_id' => 1, 'quantidade' => 3, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
            ['pedido_id' => 4, 'produto_id' => 2, 'quantidade' => 2, 'created_at' => Carbon::now(),, 'updated_at' => Carbon::now(),],
        ]);
    }
}
