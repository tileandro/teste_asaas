<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logRegisterUsersAsaas extends Model
{
    use HasFactory;

    protected $table = 'log_register_users_asaas';
    protected $fillable = ['json'];
}
