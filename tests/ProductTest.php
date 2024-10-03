<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Product;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
    }

    public function testShouldReturnAllProducts()
    {
        Product::factory()->count(3)->create();

        $this->json('GET', '/products', [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => ['id', 'nome', 'preco', 'foto']
             ]);
    }

    public function testShouldCreateProduct()
    {
        $parameters = [
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ];

        $this->json('POST', '/products', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson($parameters);

        $this->seeInDatabase('produtos', ['nome' => 'Product Test']);
    }

    public function testShouldShowProduct()
    {
        $product = Product::factory()->create();

        $this->json('GET', "/products/{$product->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $product->id, 'nome' => $product->nome]);
    }

    public function testShouldUpdateProduct()
    {
        $product = Product::factory()->create();

        $updateData = [
            'nome' => 'Updated Product',
            'preco' => 200.00
        ];

        $this->json('PUT', "/products/{$product->id}", $updateData, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson($updateData);

        $this->seeInDatabase('produtos', ['nome' => 'Updated Product']);
    }

    public function testShouldDeleteProduct()
    {
        $product = Product::factory()->create();

        $this->json('DELETE', "/products/{$product->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);

        $this->notSeeInDatabase('produtos', ['id' => $product->id]);
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
