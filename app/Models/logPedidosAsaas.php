<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logPedidosAsaas extends Model
{
    use HasFactory;

    protected $table = 'log_pedidos_asaas';
    protected $fillable = ['json'];
}
