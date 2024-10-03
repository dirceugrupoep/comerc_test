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
        Product::create([
            'nome' => 'Product Test 1',
            'preco' => 50.00,
            'foto' => 'image1.jpg',
        ]);

        Product::create([
            'nome' => 'Product Test 2',
            'preco' => 75.00,
            'foto' => 'image2.jpg',
        ]);

        Product::create([
            'nome' => 'Product Test 3',
            'preco' => 100.00,
            'foto' => 'image3.jpg',
        ]);

        $this->json('GET', 'api/v1/products', [], ['Authorization' => 'Bearer ' . $this->getToken()])
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

        $this->json('POST', 'api/v1/products', $parameters, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(201)
             ->seeJson($parameters);

        $this->seeInDatabase('produtos', ['nome' => 'Product Test']);
    }

    public function testShouldShowProduct()
    {
        $product = Product::create([
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ]);

        $this->json('GET', "api/v1/products/{$product->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson(['id' => $product->id, 'nome' => $product->nome]);
    }

    public function testShouldUpdateProduct()
    {
        $product = Product::create([
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ]);

        $updateData = [
            'nome' => 'Updated Product',
            'preco' => 200.00
        ];

        $this->json('PUT', "api/v1/products/{$product->id}", $updateData, ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200)
             ->seeJson($updateData);

        $this->seeInDatabase('produtos', ['nome' => 'Updated Product']);
    }

    public function testShouldDeleteProduct()
    {
        $product = Product::create([
            'nome' => 'Product Test',
            'preco' => 100.00,
            'foto' => 'image_url.jpg'
        ]);

        $this->json('DELETE', "api/v1/products/{$product->id}", [], ['Authorization' => 'Bearer ' . $this->getToken()])
             ->seeStatusCode(200);

        $this->notSeeInDatabase('produtos', ['id' => $product->id]);
    }

    private function getToken()
    {
        $response = $this->json('POST', 'api/v1/login', [
            'email' => 'admin@test.com',
            'password' => '@admin$123'
        ]);

        $response->seeStatusCode(200);

        $data = json_decode($response->response->getContent(), true);

        if (!isset($data['token'])) {
            $this->fail('Token not returned in the login response.');
        }

        return $data['token'];
    }
}
