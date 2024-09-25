<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nome', 'email', 'telefone', 'data_nascimento', 'endereco', 'complemento', 'bairro', 'cep'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
