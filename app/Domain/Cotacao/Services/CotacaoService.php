<?php

namespace App\Domain\Cotacao\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CotacaoService
{
    private string $BASE_URL;
    private string $API_KEY;

    public function __construct()
    {
        $this->BASE_URL = env('URL_COTACAO_ALPHAVANTAGE');
        $this->API_KEY = env('KEY_API_ALPHAVANTAGE');
    }


    /**
     * Ativo ações ou FII para cotacao 
     * ex ativo: 'BTLG11', 'B3SA3'
     * @param string $ativo
     */
    public function getCotacoes(string $ativo)
    {
        try {
            $response = Http::get($this->BASE_URL . '?function=GLOBAL_QUOTE&symbol='. $ativo . '.SA&apikey='. $this->API_KEY);

            if ($response->successful()) {
                return $response->json();
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de ações ou FII: ', [$e->getMessage()]);
        }

    }

    /**
     * Pegar cotacao para criptomoedas
     * ex codigo do ativo: 'BTC', 'ETH'
     * @param string $ativo
     * @param string $moedaRef
     */
    public function getCotacoesCripto(string $codigoAtivo, string $moedaRef = 'BRL')
    {
        try {
            $response = Http::get($this->BASE_URL . '?function=CURRENCY_EXCHANGE_RATE&from_currency='. $codigoAtivo . '&to_currency='. $moedaRef . '&apikey='. $this->API_KEY);

            if ($response->successful()) {
                return $response->json();
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de criptomoedas: ', [$e->getMessage()]);
        }

    }


    
}