<?php

namespace App\Domain\Cotacao\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CotacaoBrapiService
{
    private string $BASE_URL;

    public function __construct()
    {
        $this->BASE_URL = env('URL_COTACAO_BRAPI');
    }


    /**
     * Pegar Cotação de ações e FII 
     * ex ativo separado por virgula: 'BTLG11,B3SA3'
     * URL: https://brapi.ga/api/quote/COGN3,MGLU3
     * @param string $codigoAtivos
     */
    public function getCotacoes(string $codigoAtivos)
    {
        try {
            $response = Http::get($this->BASE_URL . 'quote/'. $codigoAtivos);

            if (!$response->successful() || $response->status() !== 200) {
                throw new \Exception('Erro na comunicação com o Brapi', $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de ações ou FII: ', [
                'Mensagem' => $e->getMessage(),
                'Linha' => $e->getLine(),
                'Codigo' => $e->getCode()
            ]);
        }

    }

    /**
     * Pegar cotacao para criptomoedas
     * ex codigo do ativo separado por virgula: 'BTC,ETH,ADA'
     * URL: https://brapi.ga/api/v2/crypto?coin=BTC&currency=BRL
     * @param string $ativo
     * @param string $moedaRef
     */
    public function getCotacoesCripto(string $codigoAtivos, string $moedaRef = 'BRL')
    {
        try {
            $response = Http::get($this->BASE_URL . 'v2/crypto?coin=' . $codigoAtivos . '&currency=' . $moedaRef);

            if (!$response->successful() || $response->status() !== 200) {
                throw new \Exception('Erro na comunicação com o Brapi', $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de criptomoedas: ', [
                'Mensagem' => $e->getMessage(),
                'Linha' => $e->getLine(),
                'Codigo' => $e->getCode()
            ]);
        }

    }

    /**
     * Pegar cotacao de moeda para coversao
     * ex codigo do ativo separado por virgula: 'USD-BRL,EUR-BRL'
     * URL: https://brapi.ga/api/v2/currency?currency=USD-BRL,EUR-BRL
     * @param string $ativo
     * @param string $moedaRef
     */
    public function getCotacaoMoedas(string $parMoeda = 'USD-BRL')
    {
        try {
            $response = Http::get($this->BASE_URL . 'v2/currency?currency='. $parMoeda);

            if (!$response->successful() || $response->status() !== 200) {
                throw new \Exception('Erro na comunicação com o Brapi', $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação de moedas: ', [
                'Mensagem' => $e->getMessage(),
                'Linha' => $e->getLine(),
                'Codigo' => $e->getCode()
            ]);
        }

    }


    
}