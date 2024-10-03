<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Client;

class ClientTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
    }

    public function testShouldReturnAllClients()
    {
        Client::create([
            'nome' => 'John Doe',
            'email' => 'john.doe@example.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => '123 Main St',
            'bairro' => 'Center',
            'cep' => '12345'
        ]);

        Client::create([
            'nome' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'telefone' => '987654321',
            'data_nascimento' => '1985-05-05',
            'endereco' => '456 Another St',
            'bairro' => 'South',
            'cep' => '54321'
        ]);

        $this->json('GET', 'api/v1/clients', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([ 
                 '*' => ['id', 'nome', 'email', 'telefone', 'data_nascimento', 'endereco', 'bairro', 'cep']
             ]);
    }

    public function testShouldCreateClient()
    {
        $parameters = [
            'nome' => 'John Doe',
            'email' => 'john.doe@example.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => '123 Main St',
            'bairro' => 'Center',
            'cep' => '12345'
        ];

        $this->json('POST', 'api/v1/clients', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson($parameters);

        $this->seeInDatabase('clientes', ['email' => 'john.doe@example.com']);
    }

    public function testShouldShowClient()
    {
        $client = Client::create([
            'nome' => 'John Doe',
            'email' => 'john.doe@example.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => '123 Main St',
            'bairro' => 'Center',
            'cep' => '12345'
        ]);

        $this->json('GET', "api/v1/clients/{$client->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson([
                 'id' => $client->id,
                 'nome' => $client->nome,
                 'email' => $client->email,
             ]);
    }

    public function testShouldUpdateClient()
    {
        $client = Client::create([
            'nome' => 'John Doe',
            'email' => 'john.doe@example.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => '123 Main St',
            'bairro' => 'Center',
            'cep' => '12345'
        ]);

        $updateData = [
            'nome' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'telefone' => '987654321',
        ];

        $this->json('PUT', "api/v1/clients/{$client->id}", $updateData, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson($updateData);

        $this->seeInDatabase('clientes', ['email' => 'jane.doe@example.com']);
    }

    public function testShouldDeleteClient()
    {
        // Criando um cliente diretamente no banco de dados
        $client = Client::create([
            'nome' => 'John Doe',
            'email' => 'john.doe@example.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => '123 Main St',
            'bairro' => 'Center',
            'cep' => '12345'
        ]);

        $this->json('DELETE', "api/v1/clients/{$client->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);

        $this->notSeeInDatabase('clientes', ['id' => $client->id]);
    }

    /**
     * Função auxiliar para obter o token de autenticação
     */
    private function getToken()
    {
        $response = $this->json('POST', '/login', [
            'email' => 'admin@test.com',
            'password' => '@admin$123'
        ]);

        $data = json_decode($response->response->getContent(), true);

        if (!isset($data['token'])) {
            $this->fail('Token not returned in the login response.');
        }

        return $data['token'];
    }
}
