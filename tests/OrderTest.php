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
        Order::factory()->count(3)->create();

        $this->json('GET', '/orders', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => ['id', 'cliente_id', 'products' => []]
             ]);
    }

    public function testShouldCreateOrder()
    {
        $client = Client::factory()->create();
        $product = Product::factory()->create();

        $parameters = [
            'cliente_id' => $client->id,
            'produtos' => [
                ['id' => $product->id, 'quantidade' => 2]
            ]
        ];

        $this->json('POST', '/orders', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson(['cliente_id' => $client->id]);

        $this->seeInDatabase('pedidos', ['cliente_id' => $client->id]);
    }

    public function testShouldShowOrder()
    {
        $order = Order::factory()->create();

        $this->json('GET', "/orders/{$order->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $order->id]);
    }

    public function testShouldUpdateOrder()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $updateData = [
            'produtos' => [
                ['id' => $product->id, 'quantidade' => 5]
            ]
        ];

        $this->json('PUT', "/orders/{$order->id}", $updateData, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $order->id]);

        $this->seeInDatabase('pedido_produto', ['produto_id' => $product->id, 'quantidade' => 5]);
    }

    public function testShouldDeleteOrder()
    {
        $order = Order::factory()->create();

        $this->json('DELETE', "/orders/{$order->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);

        $this->notSeeInDatabase('pedidos', ['id' => $order->id]);
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
