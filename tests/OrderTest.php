<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
        
        $this->client = Client::create([
            'nome' => 'Dirceu Silva de Oliveira',
            'email' => 'dirceu@test.com',
            'telefone' => '123456789',
            'data_nascimento' => '1980-01-01',
            'endereco' => 'Rua Teste, 123',
            'bairro' => 'Bairro Teste',
            'cep' => '12345-678'
        ]);

        $this->product1 = Product::create([
            'nome' => 'Pastel de Queijo',
            'preco' => 12.25,
            'foto' => 'image1.jpg'
        ]);

        $this->product2 = Product::create([
            'nome' => 'Pastel de Carne',
            'preco' => 10.99,
            'foto' => 'image2.jpg'
        ]);
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

    public function testShouldCreateOrder()
    {
        $parameters = [
            'cliente_id' => $this->client->id,
            'produtos' => [
                ['id' => $this->product1->id, 'quantidade' => 2],
                ['id' => $this->product2->id, 'quantidade' => 1],
            ]
        ];

        $this->json('POST', '/orders', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJsonStructure([
                 'id',
                 'cliente_id',
                 'products' => [
                     '*' => ['id', 'nome', 'preco', 'pivot' => ['quantidade']]
                 ],
             ]);
    }

    public function testShouldReturnAllOrders()
    {
        $order = Order::create([
            'cliente_id' => $this->client->id
        ]);

        $order->products()->attach($this->product1->id, ['quantidade' => 2]);

        $this->json('GET', '/orders', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => [
                     'id',
                     'cliente_id',
                     'products' => [
                         '*' => ['id', 'nome', 'preco', 'pivot' => ['quantidade']]
                     ]
                 ]
             ]);
    }

    public function testShouldReturnSingleOrder()
    {
        $order = Order::create([
            'cliente_id' => $this->client->id
        ]);

        $order->products()->attach($this->product1->id, ['quantidade' => 2]);

        $this->json('GET', '/orders/' . $order->id, [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 'id',
                 'cliente_id',
                 'products' => [
                     '*' => ['id', 'nome', 'preco', 'pivot' => ['quantidade']]
                 ]
             ]);
    }

    public function testShouldUpdateOrder()
    {
        $order = Order::create([
            'cliente_id' => $this->client->id
        ]);

        $order->products()->attach($this->product1->id, ['quantidade' => 2]);

        $updatedParameters = [
            'cliente_id' => $this->client->id,
            'produtos' => [
                ['id' => $this->product2->id, 'quantidade' => 3]
            ]
        ];

        $this->json('PUT', '/orders/' . $order->id, $updatedParameters, ['Authorization' => 'Bearer ' . $this->getToken()])
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'id',
                'client' => [
                    'id', 'nome', 'email', 'telefone', 'data_nascimento', 'endereco', 'bairro', 'cep'
                ],
                'products' => [
                    '*' => ['id', 'nome', 'preco', 'pivot' => ['quantidade']]
                ],
                'cliente_id'
            ])
            ->seeJson([
                'cliente_id' => (string) $this->client->id
            ]);
    }


    public function testShouldDeleteOrder()
    {
        $order = Order::create([
            'cliente_id' => $this->client->id
        ]);

        $order->products()->attach($this->product1->id, ['quantidade' => 2]);

        $this->json('DELETE', '/orders/' . $order->id, [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['message' => 'Order deleted successfully']);
    }
}
