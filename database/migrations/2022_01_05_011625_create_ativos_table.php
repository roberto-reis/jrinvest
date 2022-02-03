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
            $table->id();
            $table->string('codigo', 20);
            $table->foreignId('classe_ativo_id');
            $table->string('descricao', 50);
            $table->string('setor', 50);
            $table->timestamps();
            $table->softDeletes();

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
