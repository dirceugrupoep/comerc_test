<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return response()->json($this->productService->getAllProducts());
    }

    public function show($id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = ProductRequest::validateRequest($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product = $this->productService->createProduct($request->all());

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = ProductRequest::validateRequest($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $updatedProduct = $this->productService->updateProduct($id, $request->all());

        return response()->json($updatedProduct);
    }

    public function destroy($id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->productService->deleteProduct($id);

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
