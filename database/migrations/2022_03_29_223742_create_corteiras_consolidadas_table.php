<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorteirasConsolidadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corteiras_consolidadas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->index();
            $table->foreignUuid('ativo_id')->index();
            $table->string('quantidade_saldo');
            $table->string('preco_medio');
            $table->string('custo_total_ativo');
            $table->string('cotacao');
            $table->string('valor_total_ativo');
            $table->string('percentual');
            $table->string('rentabilidade_valor');
            $table->string('rentabilidade_percentual');
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
        Schema::dropIfExists('corteiras_consolidadas');
    }
}
