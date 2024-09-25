<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('client', 'products')->get();
    }

    public function show($id)
    {
        $order = Order::with('client', 'products')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return $order;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'cliente_id' => 'required|exists:clientes,id',
            'produtos' => 'required|array',
            'produtos.*.id' => 'exists:produtos,id',
            'produtos.*.quantidade' => 'integer|min:1',
        ]);

        $order = Order::create([
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
        $order = Order::find($id);

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
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    protected function sendOrderEmail($order)
    {
        $client = $order->client;

        Mail::to($client->email)->send(new \App\Mail\OrderDetailsMail($order));
    }
}
