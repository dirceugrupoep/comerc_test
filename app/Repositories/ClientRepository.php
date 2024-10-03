<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    protected $model;

    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    public function getAllClients()
    {
        return $this->model->all();
    }

    public function findClientById($id)
    {
        return $this->model->find($id);
    }

    public function createClient(array $data)
    {
        return $this->model->create($data);
    }

    public function updateClient($id, array $data)
    {
        $client = $this->findClientById($id);
        if ($client) {
            $client->update($data);
            return $client;
        }
        return null;
    }

    public function deleteClient($id)
    {
        $client = $this->findClientById($id);
        if ($client) {
            $client->delete();
            return true;
        }
        return false;
    }
}
