<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarteirasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carteiras', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('user_id')->index();
            $table->foreignUuid('ativo_id')->index();
            $table->string('quantidade_saldo');
            $table->string('preco_medio');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('ativo_id')->references('id')->on('ativos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carteiras');
    }
}
