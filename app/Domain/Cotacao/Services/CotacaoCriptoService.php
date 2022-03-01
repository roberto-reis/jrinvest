<?php
namespace App\Domain\Cotacao\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CotacaoCriptoService
{
    private string $BASE_URL;

    public function __construct()
    {
        $this->BASE_URL = env('URL_COTACAO_CRIPTOMOEDAS');
    }


    /**
     * Ativo e moeda de referencia para consulta separados por virgula
     * ex ativo: bitcoin,ethereum
     * ex moeda: brl,usd
     * @param string $ativos
     * @param string $moedaRef
     * @return array
     */
    public function getCotacoes(string $ativos, string $moedaRef = 'brl'): array
    {
        try {
            $response = Http::get($this->BASE_URL . 'simple/price/?ids='. $ativos . '&vs_currencies='. $moedaRef);

            if ($response->successful()) {
                return $response->json();
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de criptomoedas: ', [$e->getMessage()]);
        }

    }
    
}