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
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('classe_ativo_id');
            $table->decimal('porcentagem', 10, 2);
            $table->timestamps();
            $table->softDeletes();

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
