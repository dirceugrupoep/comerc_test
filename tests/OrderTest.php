<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
    }

    public function testShouldReturnAllOrders()
    {
        $client = Client::create([
            'nome' => 'Client Test',
            'email' => 'client@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua 123',
            'bairro' => 'Centro',
            'cep' => '12345-678'
        ]);

        $order = Order::create(['cliente_id' => $client->id]);

        $this->json('GET', 'api/v1/orders', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => ['id', 'cliente_id', 'products' => []]
             ]);
    }

    public function testShouldCreateOrder()
    {
        $client = Client::create([
            'nome' => 'Client Test',
            'email' => 'client@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua 123',
            'bairro' => 'Centro',
            'cep' => '12345-678'
        ]);

        $product = Product::create([
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ]);

        $parameters = [
            'cliente_id' => $client->id,
            'produtos' => [
                ['id' => $product->id, 'quantidade' => 2]
            ]
        ];

        $this->json('POST', 'api/v1/orders', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson(['cliente_id' => $client->id]);

        $this->seeInDatabase('pedidos', ['cliente_id' => $client->id]);
    }

    public function testShouldShowOrder()
    {
        $client = Client::create([
            'nome' => 'Client Test',
            'email' => 'client@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua 123',
            'bairro' => 'Centro',
            'cep' => '12345-678'
        ]);

        $order = Order::create(['cliente_id' => $client->id]);

        $this->json('GET', "api/v1/orders/{$order->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $order->id]);
    }

    public function testShouldUpdateOrder()
    {
        $client = Client::create([
            'nome' => 'Client Test',
            'email' => 'client@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua 123',
            'bairro' => 'Centro',
            'cep' => '12345-678'
        ]);

        $order = Order::create(['cliente_id' => $client->id]);

        $product = Product::create([
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ]);

        $updateData = [
            'produtos' => [
                ['id' => $product->id, 'quantidade' => 5]
            ]
        ];

        $this->json('PUT', "api/v1/orders/{$order->id}", $updateData, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $order->id]);

        $this->seeInDatabase('pedido_produto', ['produto_id' => $product->id, 'quantidade' => 5]);
    }

    public function testShouldDeleteOrder()
    {
        $client = Client::create([
            'nome' => 'Client Test',
            'email' => 'client@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua 123',
            'bairro' => 'Centro',
            'cep' => '12345-678'
        ]);

        $order = Order::create(['cliente_id' => $client->id]);

        $this->json('DELETE', "api/v1/orders/{$order->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);

        $this->notSeeInDatabase('pedidos', ['id' => $order->id]);
    }

    private function getToken()
    {
        $response = $this->json('POST', 'api/v1/login', [
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
