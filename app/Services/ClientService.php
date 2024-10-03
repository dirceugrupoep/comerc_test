<?php

namespace App\Services;

use App\Repositories\ClientRepository;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients()
    {
        return $this->clientRepository->getAllClients();
    }

    public function getClientById($id)
    {
        return $this->clientRepository->findClientById($id);
    }

    public function createClient(array $data)
    {
        return $this->clientRepository->createClient($data);
    }

    public function updateClient($id, array $data)
    {
        return $this->clientRepository->updateClient($id, $data);
    }

    public function deleteClient($id)
    {
        return $this->clientRepository->deleteClient($id);
    }
}
