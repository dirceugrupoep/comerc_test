<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Carbon\Carbon;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::insert([
            [
                'nome' => 'Dirceu Silva de Oliveiras',
                'email' => 'dirceu.oliveira@grupoep.com.br',
                'telefone' => '11393464695',
                'data_nascimento' => '1983-01-01',
                'endereco' => 'Rua tavares, 123',
                'bairro' => 'Bairro',
                'cep' => '12345-678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Débora Silva De Oliveira',
                'email' => 'dirceu31013375858@gmail.com',
                'telefone' => '11911459663',
                'data_nascimento' => '1983-10-01',
                'endereco' => 'Rua tavares, 123',
                'bairro' => 'Bairro',
                'cep' => '12345-678',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
