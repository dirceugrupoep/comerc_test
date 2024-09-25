<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Garantir que o usuário admin seja criado no ambiente de teste
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
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

    public function testShouldReturnAllProducts()
    {
        $this->json('GET', '/products', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);
    }

    public function testShouldCreateProduct()
    {
        $parameters = [
            'nome' => 'Product 1',
            'preco' => 19.99,
            'foto' => 'image.jpg',
        ];

        $this->json('POST', '/products', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson($parameters);
    }
}
