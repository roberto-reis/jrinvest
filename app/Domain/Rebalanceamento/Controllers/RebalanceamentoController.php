<?php

namespace App\Domain\Rebalanceamento\Controllers;

use Inertia\Inertia;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\RebalanceamentoException;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoAtivoDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;
use App\Domain\Rebalanceamento\Requests\RebalanceamentoAtivoRequest;
use App\Domain\Rebalanceamento\Requests\RebalanceamentoClasseRequest;
use App\Domain\Rebalanceamento\Actions\CreateRebalanceamentoAtivoAction;
use App\Domain\Rebalanceamento\Actions\DeleteRebalanceamentoAtivoAction;
use App\Domain\Rebalanceamento\Actions\UpdateRebalanceamentoAtivoAction;
use App\Domain\Rebalanceamento\Actions\CreateRebalanceamentoClasseAction;
use App\Domain\Rebalanceamento\Actions\DeleteRebalanceamentoClasseAction;
use App\Domain\Rebalanceamento\Actions\UpdateRebalanceamentoClasseAction;

class RebalanceamentoController extends Controller
{
    public function index()
    {

        $ativos = Ativo::orderBy('codigo', 'ASC')->get();
        $classeAtivos = ClasseAtivo::orderBy('nome', 'ASC')->get();

        $classeRebalanceamentos = RebalanceamentoClasse::with('classeAtivo')->where('user_id', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->get();
        $ativoRebalanceamentos = RebalanceamentoAtivo::with('ativo')->where('user_id', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->get();

        return Inertia::render('Rebalanceamento/Home', [
            'classeRebalanceamentos' => $classeRebalanceamentos,
            'ativoRebalanceamentos' => $ativoRebalanceamentos,
            'ativos' => $ativos,
            'classeAtivos' => $classeAtivos
        ]);
    }

    public function percentualClasseStore(RebalanceamentoClasseRequest $request, CreateRebalanceamentoClasseAction $createRebalanceamentoClasse)
    {
        $rebalanceamentoClasseDTO = RebalanceamentoClasseDTO::fromRequest($request);
        try {
            $createRebalanceamentoClasse($rebalanceamentoClasseDTO);            
            Session::flash('success', 'Rebalanceamento cadastrada com sucesso!');            
        } catch (RebalanceamentoException $e) {
            Session::flash('error', $e->getMessage());
        } catch (\Exception $e) {            
            Log::error('Error ao cadatrar rebalanceamento por classe de ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');   
    }

    public function percentualAtivoStore(RebalanceamentoAtivoRequest $request, CreateRebalanceamentoAtivoAction $createRebalanceamentoAtivo)
    {
        $rebalanceamentoAtivoDTO = RebalanceamentoAtivoDTO::fromRequest($request);
        try {
            $createRebalanceamentoAtivo($rebalanceamentoAtivoDTO);            
            Session::flash('success', 'Rebalanceamento cadastrada com sucesso!');            
        } catch (RebalanceamentoException $e) {
            Session::flash('error', $e->getMessage());
        } catch (\Exception $e) {            
            Log::error('Error ao cadatrar rebalanceamento por ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');
    }

    public function percentualClasseUpdate(RebalanceamentoClasseRequest $request, UpdateRebalanceamentoClasseAction $updateRebalanceamentoClasse)
    {   
        $rebalanceamentoClasseDTO = RebalanceamentoClasseDTO::fromRequest($request);

        try {
            $updateRebalanceamentoClasse($rebalanceamentoClasseDTO, $request->id);            
            Session::flash('success', 'Rebalanceamento atualizados com sucesso!');            
        } catch (RebalanceamentoException $e) {
            Session::flash('error', $e->getMessage());
        } catch (\Exception $e) {            
            Log::error('Error ao atualizar rebalanceamento por classe de ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');   
    }

    public function percentualAtivoUpdate(RebalanceamentoAtivoRequest $request, UpdateRebalanceamentoAtivoAction $updateRebalanceamentoAtivo)
    {   
        $rebalanceamentoAtivoDTO = RebalanceamentoAtivoDTO::fromRequest($request);

        try {
            $updateRebalanceamentoAtivo($rebalanceamentoAtivoDTO, $request->id);            
            Session::flash('success', 'Rebalanceamento atualizados com sucesso!');            
        } catch (RebalanceamentoException $e) {
            Session::flash('error', $e->getMessage());
        } catch (\Exception $e) {            
            Log::error('Error ao atualizar rebalanceamento por ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');   
    }

    public function percentualClasseDestroy(DeleteRebalanceamentoClasseAction $deleteRebalanceamento, $id)
    {
        try {
            $deleteRebalanceamento($id);
            Session::flash('success', 'Rebalanceamento excluída com sucesso!');            
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            Log::error('error ao excluir rebalanceamento de classe de ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');
    }

    public function percentualAtivoDestroy(DeleteRebalanceamentoAtivoAction $deleteRebalanceamento, $id)
    {
        try {
            $deleteRebalanceamento($id);
            Session::flash('success', 'Rebalanceamento excluída com sucesso!');            
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            Log::error('error ao excluir rebalanceamento de ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');
    }
}
