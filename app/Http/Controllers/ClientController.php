<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index()
    {
        return response()->json($this->clientService->getAllClients());
    }

    public function show($id)
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    public function store(Request $request)
    {
        $validator = ClientRequest::validateRequest($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $client = $this->clientService->createClient($request->all());

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $validator = ClientRequest::validateRequest($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $updatedClient = $this->clientService->updateClient($id, $request->all());

        return response()->json($updatedClient);
    }

    public function destroy($id)
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $this->clientService->deleteClient($id);

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
