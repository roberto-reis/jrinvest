<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
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

            $operacoes = Operacao::where('user_id', $this->usuarioLogado->id)
                ->get();

            $this->montaCarteiraUsuario($operacoes);

            ConsolidaCarteiraUserJob::dispatch($this->usuarioLogado);
            
        } catch (\Exception $e) {
            Log::error('Error ao montar carteira: ', [
                'Menssagem' => $e->getMessage(),
                'JOB' => __CLASS__,
                'Linha' => $e->getLine(),
            ]);
        }
    }

    /**
     * Monta a carteira do usuário
     *
     * @return void
     */
    private function montaCarteiraUsuario($operacoes)
    {
        if ($operacoes->isEmpty()) {
            throw new \Exception('Nenhuma operação encontrada para o usuário');
        }

        $operacoesAtivos = $operacoes->groupBy('ativo_id');

        foreach ($operacoesAtivos as $operacoes) {

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
    }
}
