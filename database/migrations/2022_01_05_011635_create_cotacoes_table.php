<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ativo_id')->index();
            $table->char('moeda_ref', 3);
            $table->double('preco', 15, 8)->index();
            $table->timestamps();

            $table->foreign('ativo_id')->references('id')->on('ativos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotacoes');
    }
}
