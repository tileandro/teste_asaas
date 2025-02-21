<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserNovasColunas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_user_asaas', 255)->after('email')->nullable();
            $table->string('phone', 20)->after('id_user_asaas')->nullable();
            $table->string('cpf', 20)->after('phone')->nullable();
            $table->string('cnpj', 20)->after('cpf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_user_asaas');
            $table->dropColumn('phone');
            $table->dropColumn('cpf');
            $table->dropColumn('cnpj');
        });
    }
}
