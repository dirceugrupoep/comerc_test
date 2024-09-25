<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'pedido_produto', 'pedido_id', 'produto_id')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
