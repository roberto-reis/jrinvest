<?php

namespace App\Domain\Operacao\Controllers;

use Inertia\Inertia;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Domain\Operacao\DTO\OperacaoDTO;
use App\Domain\Operacao\Models\Operacao;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Operacao\Exports\OperacaoExport;
use App\Domain\Operacao\Requests\OperacaoRequest;
use App\Domain\Operacao\Actions\CreateOperacaoAction;
use App\Domain\Operacao\Actions\DeleteOperacaoAction;
use App\Domain\Operacao\Actions\UpdateOperacaoAction;

class OperacaoController extends Controller
{
    private $perPage = 10;

    public function index()
    {
        $search = request()->get('search');
        $this->perPage = request()->get('perPage', $this->perPage);
        $field = request()->get('field', 'data_operacao');
        $direction = request()->get('direction', 'desc');

        $operacoes = Operacao::query()->select('operacoes.*', 'ativos.codigo as codigo_ativo', 'classes_ativos.nome as classe_ativo')
                    ->join('ativos', 'operacoes.ativo_id', '=', 'ativos.id')
                    ->join('classes_ativos', 'ativos.classe_ativo_id', '=', 'classes_ativos.id')
                    ->where('user_id', auth()->user()->id);

        if ($search) {
            $operacoes->where('tipo_operacao', 'like', "%{$search}%")
                ->orWhere('corretora', 'like', "%{$search}%")
                ->orWhere('ativos.codigo', 'like', "%{$search}%")
                ->orWhere('classes_ativos.nome', 'like', "%{$search}%")
                ->orWhere('operacoes.data_operacao', 'like', "%{$search}%");
        }

        if ($field && $direction) {
            $operacoes->orderBy($field, $direction);
        }

        $ativos = Ativo::query()->orderBy('codigo', 'asc')->get();

        return Inertia::render('Operacoes/Home', [
            'operacoes' => $operacoes->paginate($this->perPage),
            'ativos' => $ativos,
            'filters' => [
                'search' => $search,
                'field' => $field,
                'direction' => $direction,
                'perPage' => $this->perPage,
            ],
        ]);
    }

    public function store(OperacaoRequest $requestOperacao, CreateOperacaoAction $actionCreateOperacao)
    {
        $operacaoDTO = OperacaoDTO::fromRequest($requestOperacao);
        try {
            $actionCreateOperacao($operacaoDTO);
            Session::flash('success', 'Operação cadastrada com sucesso!');            
        } catch (\Exception $e) {
            Log::error('error ao savar operação: ', [$e->getMessage()]);
        }

        return Redirect::route('operacoes.index');
    }

    public function update(OperacaoRequest $requestOperacao, UpdateOperacaoAction $actionUpdateOperacao)
    {        
        $operacaoDTO = OperacaoDTO::fromRequest($requestOperacao);

        try {

            $actionUpdateOperacao($operacaoDTO, $requestOperacao->id);
            Session::flash('success', 'Operação atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('error ao atualizar operação: ', [$e->getMessage()]);
        }
        return Redirect::route('operacoes.index');
    }

    public function destroy(DeleteOperacaoAction $deleteOperacaoAction, $id)
    {
        try {
            $deleteOperacaoAction($id);
            Session::flash('success', 'Operação excluída com sucesso!');
            return Redirect::route('operacoes.index');
        } catch (\Exception $e) {
            Log::error('error ao excluir operação: ', [$e->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new OperacaoExport(), 'operacoes.xlsx');
    }

}
