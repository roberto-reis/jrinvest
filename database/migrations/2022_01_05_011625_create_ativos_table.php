<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ativos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo', 20)->unique()->index();
            $table->foreignUuid('classe_ativo_id');
            $table->string('nome', 50)->index();
            $table->string('setor', 50)->index();
            $table->timestamps();

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
        Schema::dropIfExists('ativos');
    }
}
