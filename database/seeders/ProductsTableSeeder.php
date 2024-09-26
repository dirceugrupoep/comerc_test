<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            [
                'nome' => 'Pastel de Carne',
                'preco' => 12.50,
                'foto' => 'img/image.jpg',
                'created_at' => Carbon::now(),,
                'updated_at' => Carbon::now(),,
            ],
            [
                'nome' => 'Pastel de Queijo',
                'preco' => 12.50,
                'foto' => 'img/image.jpg',
                'created_at' => Carbon::now(),,
                'updated_at' => Carbon::now(),,
            ],
            [
                'nome' => 'Pastel de Frango',
                'preco' => 12.50,
                'foto' => 'img/image.jpg',
                'created_at' => Carbon::now(),,
                'updated_at' => Carbon::now(),,
            ],
            [
                'nome' => 'Pastel Especial',
                'preco' => 22.75,
                'foto' => 'img/image.jpg',
                'created_at' => Carbon::now(),,
                'updated_at' => Carbon::now(),,
            ]
        ]);
    }
}
