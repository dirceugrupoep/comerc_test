<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderRequest
{
    public static function validateRequest(Request $request)
    {
        $rules = [
            'cliente_id' => 'required|exists:clientes,id',
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'integer|min:1',
        ];

        return Validator::make($request->all(), $rules);
    }
}
