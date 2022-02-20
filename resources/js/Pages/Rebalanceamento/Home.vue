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
                        <div class="tab-pane fade show active" id="tab_classe_ativo" role="tabpanel" aria-labelledby="tabs-classe-ativo">
                            <div class="mb-1">
                                <h2 class="text-gray-700 text-lg font-semibold">
                                    Selecione uma classe de um ativo e porcentagem(%) Meta/Objetivo
                                </h2>
                            </div>

                            <div class="p-3 bg-gray-100 rounded-lg">
                                <form>
                                    <div class="flex flex-col sm:flex-row">
                                        <div class="w-full mr-3 mb-3">
                                            <label for="input_classe_ativo" class="block mb-1 text-sm font-medium text-gray-900">Classe de Ativo:</label>
                                            <input type="text" id="input_classe_ativo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="name@flowbite.com" required>
                                        </div>
                                        <div class="w-full mr-3 mb-3">
                                            <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                                            <input type="text" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15,00" required>
                                        </div>
                                        <div class="flex items-end mb-3">
                                            <button type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded text-sm sm:w-auto px-5 py-2.5 text-center">
                                                Incluir
                                            </button>
                                        </div>
                                    </div>                                
                                </form>

                                <div class="flex flex-col">
                                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                        <div class="overflow-hidden inline-block min-w-full align-middle">
                                            <table class="min-w-full divide-y divide-gray-400 table-fixed">
                                                <thead class="bg-gray-300 text-gray-600 text-xs font-semibold">
                                                    <tr>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Classe
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Descrição
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            % Meta/Objetivo
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Ações
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-900">
                                                    <tr class="hover:bg-gray-100">
                                                        <td class="py-2 px-4 whitespace-nowrap">Cripto</td>
                                                        <td class="py-2 px-4 whitespace-nowrap">Criptomoeda</td>
                                                        <td class="py-2 px-4 whitespace-nowrap">15,00 %</td>
                                                        <td class="py-2 px-4 text-sm font-medium whitespace-nowrap">
                                                            <button class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB REBALANCEAMENTO POR ATIVO -->
                        <div class="tab-pane fade" id="tab_ativo" role="tabpanel" aria-labelledby="tabs-ativo">

                            <div class="mb-1 text-gray-700 text-lg font-semibold">
                                <h2>
                                    Selecione um ativo e porcentagem(%) Meta/Objetivo
                                </h2>
                            </div>
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <form>
                                    <div class="flex flex-col sm:flex-row">
                                        <div class="w-full mr-3 mb-3">
                                            <label for="input_classe_ativo" class="block mb-1 text-sm font-medium text-gray-900">Ativo:</label>
                                            <input type="text" id="input_classe_ativo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="name@flowbite.com" required>
                                        </div>
                                        <div class="w-full mr-3 mb-3">
                                            <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                                            <input type="text" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15,00" required>
                                        </div>
                                        <div class="flex items-end mb-3">
                                            <button type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded text-sm sm:w-auto px-5 py-2.5 text-center">
                                                Incluir
                                            </button>
                                        </div>
                                    </div>                                
                                </form>

                                <div class="flex flex-col">
                                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                        <div class="overflow-hidden inline-block min-w-full align-middle">
                                            <table class="min-w-full divide-y divide-gray-400 table-fixed">
                                                <thead class="bg-gray-300 text-gray-600 text-xs font-semibold">
                                                    <tr>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Classe
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Descrição
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            % Meta/Objetivo
                                                        </th>
                                                        <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                                            Ações
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-900">
                                                    <tr class="hover:bg-gray-100">
                                                        <td class="py-2 px-4 whitespace-nowrap">Cripto</td>
                                                        <td class="py-2 px-4 whitespace-nowrap">Criptomoeda</td>
                                                        <td class="py-2 px-4 whitespace-nowrap">15,00 %</td>
                                                        <td class="py-2 px-4 text-sm font-medium whitespace-nowrap">
                                                            <button class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>                
            </div>
        </div>
    </section>

  </Authenticated>

</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";

export default {
  name: 'Rebalanceamento',
  props: {
    ativoRebalanceamentos: Object,
    classeRebalanceamentos: Object,
  },
  components: {
    Authenticated,
    Head,
  },
};
</script>
