<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $fillable = ['produto_id', 'user_id', 'metodo_pagamento', 'valor_total', 'valor_parcela', 'data_vencimento', 'link_boleto', 'pix_copia_cola', 'pix_qr_code'];
}
