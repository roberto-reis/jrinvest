<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRebalanceamentoClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebalanceamento_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->foreignUuid('classe_ativo_id');
            $table->decimal('porcentagem', 10, 2);
            $table->timestamps();
            $table->unique(['user_id', 'classe_ativo_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('classe_ativo_id')->references('id')->on('classes_ativos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rebalanceamento_classes');
    }
}
