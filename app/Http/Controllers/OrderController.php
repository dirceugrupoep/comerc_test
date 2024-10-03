<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return response()->json($this->orderService->getAllOrders());
    }

    public function show($id)
    {
        $order = $this->orderService->getOrderById($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validator = OrderRequest::validateRequest($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = $this->orderService->createOrder([
            'cliente_id' => $request->cliente_id,
        ]);

        foreach ($request->produtos as $produto) {
            $order->products()->attach($produto['id'], ['quantidade' => $produto['quantidade'] ?? 1]);
        }

        $this->sendOrderEmail($order);

        return response()->json($order->load('client', 'products'), 201);
    }

    public function update(Request $request, $id)
    {
        $order = $this->orderService->getOrderById($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->products()->detach();

        foreach ($request->produtos as $produto) {
            $order->products()->attach($produto['id'], ['quantidade' => $produto['quantidade'] ?? 1]);
        }

        return response()->json($order->load('client', 'products'), 200);
    }

    public function destroy($id)
    {
        $order = $this->orderService->getOrderById($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $this->orderService->deleteOrder($id);

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    protected function sendOrderEmail($order)
    {
        $client = $order->client;

        Mail::to($client->email)->send(new \App\Mail\OrderDetailsMail($order));
    }
}
