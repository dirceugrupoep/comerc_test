<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientRequest
{
    public static function validateRequest(Request $request)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes',
            'telefone' => 'required|string|max:15',
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:100',
            'cep' => 'required|string|max:10',
        ];

        return Validator::make($request->all(), $rules);
    }
}