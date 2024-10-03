<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function getAllOrders()
    {
        return $this->model->with('client', 'products')->get();
    }

    public function findOrderById($id)
    {
        return $this->model->with('client', 'products')->find($id);
    }

    public function createOrder(array $data)
    {
        return $this->model->create($data);
    }

    public function updateOrder($id, array $data)
    {
        $order = $this->findOrderById($id);
        if ($order) {
            $order->update($data);
            return $order;
        }
        return null;
    }

    public function deleteOrder($id)
    {
        $order = $this->findOrderById($id);
        if ($order) {
            $order->delete();
            return true;
        }
        return false;
    }
}
