<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Operacao\Models\Operacao;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MontaCarteiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $usuarioLogado;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->usuarioLogado = Auth::user();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {            

            $operacoesAtivos = Operacao::select('operacoes.*', 'ativos.codigo as codigo_ativo')
                ->join('ativos', 'operacoes.ativo_id', '=', 'ativos.id')
                ->where('user_id', $this->usuarioLogado->id)
                ->get();


            foreach ($operacoesAtivos->groupBy('codigo_ativo') as $operacoes) {

                $newOperacao = $operacoes->first();
                $somaOperacoesCompras = $operacoes->where('tipo_operacao', 'compra')->sum('quantidade');
                $somaOperacoesVendas = $operacoes->where('tipo_operacao', 'venda')->sum('quantidade');
                $somaValorTotal = $operacoes->where('tipo_operacao', 'compra')->sum('valor_total');

                // Salva ou atualiza a composição da carteira
                Carteira::updateOrCreate([
                    'user_id' => $newOperacao->user_id,
                    'ativo_id' => $newOperacao->ativo_id,
                ],
                [
                    'quantidade_saldo' => ($somaOperacoesCompras - $somaOperacoesVendas),
                    'preco_medio' => ($somaValorTotal / $somaOperacoesCompras),
                ]);
                
            }


        } catch (\Exception $e) {
            Log::error('Error ao montar carteira: ', [$e->getMessage()]);
        }
    }
}
