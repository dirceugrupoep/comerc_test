<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

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
        $this->json('GET', '/clients', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);
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

        $this->json('POST', '/clients', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson($parameters);
    }

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

