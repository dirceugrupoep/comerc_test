<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')
                ->constrained('pedidos')
                ->onDelete('cascade');
            $table->foreignId('produto_id')
                ->constrained('produtos')
                ->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_produto');
        Schema::dropIfExists('pedidos');
    }
};
