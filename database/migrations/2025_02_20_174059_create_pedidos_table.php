<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
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
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('user_id');
            $table->string('metodo_pagamento', 50);
            $table->float('valor_total', 8, 2);
            $table->float('valor_parcela', 8, 2)->nullable();
            $table->string('numero_parcela_cartao')->nullable();
            $table->date('data_vencimento');
            $table->string('link_boleto', 255)->nullable();
            $table->text('pix_copia_cola', 255)->nullable();
            $table->text('pix_qr_code', 255)->nullable();
            $table->string('status_pedido_asaas', '50');
            $table->timestamps();

            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_produto_id_foreign');
            $table->dropForeign('pedidos_user_id_foreign');
            $table->dropColumn(['produto_id', 'user_id']);
        });

        Schema::dropIfExists('pedidos');
    }
}
