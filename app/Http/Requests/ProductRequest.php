<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductRequest
{
    public static function validateRequest(Request $request)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'foto' => 'nullable|string',
        ];

        return Validator::make($request->all(), $rules);
    }
}
