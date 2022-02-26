<template>
  <Head title="Rebalanceamento de Carteira" />
  <Authenticated>

    <template #breadcrumb>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Rebalanceamento de Carteira
      </h2>
    </template>

    <section class="mt-6">
        <div class="bg-white overflow-hidden shadow-md rounded">
            <div class="p-3 bg-white border-b border-gray-200 flex">
                <div class="w-full max-w-4xl">

                    <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b border-gray-200 pl-0 mb-4" id="tabs-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#tab_classe_ativo" class="nav-link active block font-semibold text-xs leading-tight uppercase border-x-0 border-t-0 border-b-2 border-transparent px-6 py-3 hover:border-transparent hover:bg-gray-100 focus:border-transparent"
                                id="tabs-classe-ativo" data-bs-toggle="pill" data-bs-target="#tab_classe_ativo" role="tab" aria-controls="tab_classe_ativo" aria-selected="true">
                                Classe de Ativo
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab_ativo" class="nav-link block font-semibold text-xs leading-tight uppercase border-x-0 border-t-0 border-b-2 border-transparent px-6 py-3 hover:border-transparent hover:bg-gray-100 focus:border-transparent" 
                                id="tabs-ativo" data-bs-toggle="pill" data-bs-target="#tab_ativo" role="tab" aria-controls="tab_ativo" aria-selected="false">
                                Ativo
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tabs-tabContent">
                        <!-- TAB REBALANCEAMENTO POR CLASSE DE ATIVO -->
                        <RebalanceamentoClasse :id="'tab_classe_ativo'" :classeRebalanceamentos="classeRebalanceamentos" :classeAtivos="classeAtivos" />

                        <!-- TAB REBALANCEAMENTO POR ATIVO -->
                        <RebalanceamentoAtivo :id="'tab_ativo'" :ativoRebalanceamentos="ativoRebalanceamentos" :ativos="ativos" />
                        
                    </div>

                </div>                
            </div>
        </div>
    </section>

    <!-- Notificações -->
	<ToastSuccess :menssagem="flash.success" />

  </Authenticated>

</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import ToastSuccess from "@/Components/ToastSuccess.vue";
import RebalanceamentoClasse from "./RebalanceamentoClasse.vue";
import RebalanceamentoAtivo from "./RebalanceamentoAtivo.vue";

export default {
    name: 'Rebalanceamento',
    props: {
        classeRebalanceamentos: Object,
        ativoRebalanceamentos: Object,
        classeAtivos: Object,
        ativos: Object,
        flash: Object,
    },
    components: {
        Authenticated,
        Head,
        ToastSuccess,
        RebalanceamentoClasse,
        RebalanceamentoAtivo,
    },
    data() {
        return {};
    },
    methods: {}
};
</script>
