<template>
<Head title="Dashboard" />
<Authenticated>

  <template #breadcrumb>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Dashboard
    </h2>
  </template>

  <!-- Section Rentabilidades -->
  <section class="mt-6 overflow-hidden">
    <div class="flex justify-between items-center">
    
      <div class="mr-2 basis-1/4">
        <div class="block p-4 rounded-lg shadow-lg bg-white max-w-sm text-center border-2" :class="rentabidadeCarteiraHoje.rentabilidade_valor > 0 ? 'border-green-500' : 'border-red-500' ">
          <p class="font-bold text-lg mb-4" :class="rentabidadeCarteiraHoje.rentabilidade_valor > 0 ? 'text-green-700' : 'text-red-700' ">
            <span>{{ formatMoneyBr(rentabidadeCarteiraHoje.rentabilidade_valor) }} | ({{ numberFormatterBr(rentabidadeCarteiraHoje.rentabilidade_percentual) }}%)</span>
            <span>
              <i class="fas ml-2" :class="rentabidadeCarteiraHoje.rentabilidade_valor > 0 ? 'fa-arrow-up' : 'fa-arrow-down' "></i>
            </span>
          </p>
          <h5 class="text-gray-700 text-lg leading-tight font-medium">Rentabilidade (Hoje)</h5>
        </div>
      </div>

      <div class="mr-2 basis-1/4">
        <div class="block p-4 rounded-lg shadow-lg bg-white max-w-sm text-center border-2" :class="rentabidadeCarteira30Dias.rentabilidade_valor > 0 ? 'border-green-500' : 'border-red-500' ">
          <p class="font-bold text-lg mb-4" :class="rentabidadeCarteira30Dias.rentabilidade_valor > 0 ? 'text-green-700' : 'text-red-700' ">
            <span>{{ formatMoneyBr(rentabidadeCarteira30Dias.rentabilidade_valor) }} | ({{ numberFormatterBr(rentabidadeCarteira30Dias.rentabilidade_percentual) }}%)</span>
            <span>
              <i class="fas ml-2" :class="rentabidadeCarteira30Dias.rentabilidade_valor > 0 ? 'fa-arrow-up' : 'fa-arrow-down' "></i>
            </span>
          </p>
          <h5 class="text-gray-700 text-lg leading-tight font-medium">Rentabilidade (últimos 30 dia)</h5>
        </div>
      </div>

      <div class="basis-1/4">
        <div class="block p-4 rounded-lg shadow-lg bg-white max-w-sm text-center border-2" :class="rentabidadeCarteira180Dias.rentabilidade_valor > 0 ? 'border-green-500' : 'border-red-500' ">
          <p class="font-bold text-lg mb-4" :class="rentabidadeCarteira180Dias.rentabilidade_valor > 0 ? 'text-green-700' : 'text-red-700' ">
            <span>{{ formatMoneyBr(rentabidadeCarteira180Dias.rentabilidade_valor) }} | ({{ numberFormatterBr(rentabidadeCarteira180Dias.rentabilidade_percentual) }}%)</span>
            <span>
              <i class="fas ml-2" :class="rentabidadeCarteira180Dias.rentabilidade_valor > 0 ? 'fa-arrow-up' : 'fa-arrow-down' "></i>
            </span>
          </p>
          <h5 class="text-gray-700 text-lg leading-tight font-medium">Rentabilidade (últimos 6 meses)</h5>
        </div> 
      </div>

      <div class="ml-2 basis-1/4">
        <div class="block p-4 rounded-lg shadow-lg bg-white max-w-sm text-center border-2" :class="rentabidadeCarteira365Dias.rentabilidade_valor > 0 ? 'border-green-500' : 'border-red-500' ">
          <p class="font-bold text-lg mb-4" :class="rentabidadeCarteira365Dias.rentabilidade_valor > 0 ? 'text-green-700' : 'text-red-700' ">
            <span>{{ formatMoneyBr(rentabidadeCarteira365Dias.rentabilidade_valor) }} | ({{ numberFormatterBr(rentabidadeCarteira365Dias.rentabilidade_percentual) }}%)</span>
            <span>
              <i class="fas ml-2" :class="rentabidadeCarteira365Dias.rentabilidade_valor > 0 ? 'fa-arrow-up' : 'fa-arrow-down' "></i>
            </span>
          </p>
          <h5 class="text-gray-700 text-lg leading-tight font-medium">Rentabilidade (últimos 12 meses)</h5>
        </div>
      </div>

    </div>
  </section>

  <!-- Section rebalanceamento por Ativo -->
  <section class="mt-6">
    <div class="bg-white overflow-hidden shadow-sm rounded">
      <div class="p-4 border-b border-gray-200 flex flex-col">

        <div class="text-2xl font-bold text-gray-500 mb-2">
          <h3>Rebalanceamento por Ativo</h3>
        </div>
        
        <!-- ROW Rebalanceamento por Ativo-->
        <div class="flex items-start"> 

          <!-- Card Minha Carteira-->
          <div class="mr-2 block rounded-lg shadow-lg bg-white text-center border border-gray-300 basis-1/3">
            <div class="py-2 px-3 border-b rounded-t-lg border-gray-300 bg-gray-200">
              <h5 class="text-gray-700 text-base font-semibold">Minha Carteira ({{ formatMoneyBr(minhaCarteira.valor_total_carteira) }})</h5>
            </div>            
            <div class="p-2">
              <div class="overflow-x-auto">
                <table class="min-w-full">
                  <thead class="border-b text-sm font-medium text-gray-700 text-left bg-slate-100">
                    <tr>
                      <th scope="col" class="py-1.5 px-2.5">
                        Ativo
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        QT. Cota
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        Valor R$
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        %
                      </th>
                    </tr>
                  </thead>
                  <tbody v-for="item in minhaCarteira.ativos" :key="item.id" class="text-sm font-medium text-gray-700 text-left">
                    <tr class="bg-white border-b">
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ item.codigo }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ numberFormatterBr(item.quantidade_saldo, 4) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ formatMoneyBr(item.valor_ativo) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap font-semibold">
                        {{ numberFormatterBr(item.percentual) }}%
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>


          </div>

          <!-- Card Posição Ideal Por Ativo-->
          <div class="block rounded-lg shadow-lg bg-white text-center border border-gray-300 basis-1/3">
            <div class="py-2 px-3 border-b rounded-t-lg border-gray-300 bg-gray-200">
              <h5 class="text-gray-700 text-base font-semibold">Carteira Ideal</h5>
            </div>            
            <div class="p-2 min-w-full">
              <div class="overflow-x-auto">
                <table class="min-w-full">
                  <thead class="border-b text-sm font-medium text-gray-700 text-left bg-slate-100">
                    <tr>
                      <th scope="col" class="py-1.5 px-2.5">
                        Ativo
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        QT. Cota
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        Valor R$
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        %
                      </th>
                    </tr>
                  </thead>
                  <tbody class="text-sm font-medium text-gray-900 text-left">
                    <tr v-for="item in carteiraIdeal.ativos" :key="item.id" class="bg-white border-b">
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ item.codigo }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ numberFormatterBr(item.quantidade_ativo, 4) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ formatMoneyBr(item.valor_ativo) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap font-semibold">
                        {{ numberFormatterBr(item.percentual) }}%
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Card Ajuste de Posição-->
          <div class="ml-2 block rounded-lg shadow-lg bg-white text-center border border-gray-300 basis-1/3">
            <div class="py-2 px-3 border-b rounded-t-lg border-gray-300 bg-gray-200">
              <h5 class="text-gray-700 text-base font-semibold">Ajuste na Carteira</h5>
            </div>            
            <div class="p-2 min-w-full">
              <div class="overflow-x-auto">
                <table class="min-w-full">
                  <thead class="border-b text-sm font-medium text-gray-700 text-left bg-slate-100">
                    <tr>
                      <th scope="col" class="py-1.5 px-2.5">
                        Ativo
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        QT. Cota
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        Valor R$
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        %
                      </th>
                      <th scope="col" class="py-1.5 px-2.5">
                        Ação
                      </th>
                    </tr>
                  </thead>
                  <tbody class="text-sm font-medium text-gray-900 text-left">
                    <tr v-for="item in carteiraAjuste.ativos" :key="item.id" class="bg-white border-b">
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        {{ item.codigo }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap" :class="item.quantidade_ativo > 0 ? 'text-green-600' : 'text-red-600'">
                        {{ numberFormatterBr(item.quantidade_ativo, 4) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap" :class="item.valor_ativo > 0 ? 'text-green-600' : 'text-red-600'">
                        {{ formatMoneyBr(item.valor_ativo) }}
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap font-semibold" :class="item.percentual > 0 ? 'text-green-600' : 'text-red-600'">
                        {{ numberFormatterBr(item.percentual) }}%
                      </td>
                      <td class="py-1.5 px-2.5 whitespace-nowrap">
                        <span class="text-xs inline-block py-1 px-2 leading-none text-center font-bold text-white rounded"
                          :class="item.valor_ativo > 0 ? 'bg-green-400' : 'bg-red-400'">
                          {{ item.valor_ativo > 0 ? 'comprar' : 'vender' }}
                        </span>
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
  </section>

  <!-- Section rebalanceamento por Categoria -->
  <section class="mt-6">
    <div class="bg-white overflow-hidden shadow-sm rounded">
      <div class="p-4 border-b border-gray-200 flex flex-col">
        
        <div class="text-2xl font-bold text-gray-500 mb-2">
          <h3>Rebalanceamento por Classe de Ativo</h3>
        </div>
        
        <!-- ROW Rebalanceamento por Ativo-->
        <div class="flex items-start"> 

          <!-- Card Minha Carteira-->
          <div class="mr-2 block rounded-lg shadow-lg bg-white text-center border border-gray-300 basis-1/2">
            <!-- Card Body-->         
            <div class="p-2">
              <div>
                <pie-chart :data="minhaCarteiraChart"></pie-chart>
              </div>
            </div>
            <!-- Card Header-->
            <div class="py-2 px-3 border-t rounded-b-lg border-gray-300 bg-gray-200">
              <h5 class="text-gray-700 text-base font-semibold">Minha Carteira</h5>
            </div>
          </div>

          <!-- Card Minha Carteira-->
          <div class="mr-2 block rounded-lg shadow-lg bg-white text-center border border-gray-300 basis-1/2">
            <!-- Card Body-->         
            <div class="p-2">
              <div>
                <pie-chart :data="carteiraIdealChart"></pie-chart>
              </div>
            </div>
            <!-- Card Header-->
            <div class="py-2 px-3 border-t rounded-b-lg border-gray-300 bg-gray-200">
              <h5 class="text-gray-700 text-base font-semibold">Carteira Ideal</h5>
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
import { formatMoneyBr, numberFormatterBr } from '@/Helpers/index.js';

export default {
  name: 'Dashboard',
  components: {
    Authenticated,
    Head,
  },
  props: {
    minhaCarteira: Object,
    carteiraIdeal: Object,
    carteiraAjuste: Object,
    minhaCarteiraPorClasses: Object,
    carteiraIdealPorClasse: Object,
    rentabidadeCarteiraHoje: Object,
    rentabidadeCarteira30Dias: Object,
    rentabidadeCarteira180Dias: Object,
    rentabidadeCarteira365Dias: Object,
  },
  data() {
    return {
      minhaCarteiraChart: {},
      carteiraIdealChart: {},
    };
  },
  methods: {
    formatMoneyBr,
    numberFormatterBr,
  },
  created() {
    this.minhaCarteiraChart = Object.values(this.minhaCarteiraPorClasses).map((item) => {
      return [ item.classe_ativo, item.percentual ];
    });
    this.carteiraIdealChart = Object.values(this.carteiraIdealPorClasse).map((item) => {
      return [ item.classe_ativo, item.percentual ];
    });

  }
};
</script>
