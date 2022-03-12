<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRebalanceamentoAtivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebalanceamento_ativos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->index();
            $table->foreignUuid('ativo_id')->index();
            $table->decimal('percentual', 10, 2);
            $table->timestamps();
            $table->unique(['user_id', 'ativo_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ativo_id')->references('id')->on('ativos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rebalanceamento_ativos');
    }
}
