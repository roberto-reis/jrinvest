<template>
  <Head title="Operações" />
  <Authenticated>

    <template #breadcrumb>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Operações
      </h2>
    </template>

		<section class="mt-6">
			<div class="bg-white overflow-hidden shadow-sm rounded">
				<div class="p-4 bg-white border-b border-gray-200 flex justify-between">
					<div>
						<VButton class="bg-green-600 hover:bg-green-700 focus:bg-green-700">
							Novo Aporte
						</VButton>
						<VButton class="ml-2 bg-red-700 hover:bg-red-800 focus:bg-red-800">
							Nova Venda
						</VButton>
						<VButton class="ml-2 bg-gray-500 hover:bg-gray-700 focus:bg-gray-600">
							Export
						</VButton>
					</div>
					<form>
						<div class="flex w-96">
							<input type="text" v-model="params.search" placeholder="O que deseja buscar?" 
								class="rounded-none rounded-l border-2 border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5">
							<span class="inline-flex items-center px-3 text-sm bg-blue-600 text-white rounded-r border border-l-0 border-blue-600">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</form>
				</div>
			</div>
		</section>

		<section class="mt-6">
			<div class="bg-white overflow-hidden shadow-sm rounded">
				<div class="p-4 bg-white border-b border-gray-200">
					
					<div class="flex flex-col">
						<div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
							<div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
								<div class="overflow-x-auto">
									<table class="min-w-full">
										<thead class="border-b text-sm text-left font-medium text-gray-700">
											<tr>
												<th scope="col" @click="sort('classe_ativo')" class="px-2 py-2">
													<span>Ativo</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('tipo_operacao')" class="px-2 py-2">
													<span>Operação</span>
													<i class="fas fa-sort ml-3"></i>	
												</th>
												<th scope="col" @click="sort('classe_ativo')" class="px-2 py-2">
													<span>Classe Ativo</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('quantidade')" class="px-2 py-2">
													<span>Qtd.</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('cotacao_preco')" class="px-2 py-2">
													<span>Cotação</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('valor_total')" class="px-2 py-2">
													<span>Valor Total</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('corretora')" class="px-2 py-2">
													<span>Corretora</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" @click="sort('created_at')" class="px-2 py-2">
													<span>Data</span>
													<i class="fas fa-sort ml-3"></i>
												</th>
												<th scope="col" class="px-2 py-2">
													<span>Ações</span>
												</th>
											</tr>
										</thead>
										<tbody>

											<tr class="border-b" v-for="operacao in operacoes.data" :key="operacao.id">
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ operacao.codigo_ativo }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													<span :class="operacao.tipo_operacao == 'venda' ? 'bg-green-600' :'bg-red-600'" class="text-xs inline-block py-1 px-1.5 leading-none text-center whitespace-nowrap align-baseline font-bold text-white rounded">
														{{ operacao.tipo_operacao}}
													</span>
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ operacao.classe_ativo}}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ operacao.quantidade }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ formatMoneyBr(operacao.cotacao_preco) }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ formatMoneyBr(operacao.valor_total) }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ operacao.corretora }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													{{ formatDateBr(operacao.created_at) }}
												</td>
												<td class="text-sm text-gray-900 font-light px-2 py-3 whitespace-nowrap">
													<div class="btn-group" role="group">
														<button type="button" @click="setOperacaoEdit(operacao)" class="inline-block px-2.5 py-2 bg-yellow-500 text-white font-medium text-xs leading-tight rounded hover:bg-yellow-600 hover:shadow-lg focus:bg-yellow-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-yellow-700 active:shadow-lg transition duration-150 ease-in-out">
															<i class="fas fa-edit"></i>
														</button>
														<button type="button" class="inline-block ml-1 px-2.5 py-2 bg-red-600 text-white font-medium text-xs leading-tight rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">
															<i class="fas fa-trash"></i>
														</button>
													</div>
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

		<Pagination :data="operacoes" />


  </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import VButton from "@/Components/Button.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { formatMoneyBr, formatDateBr, getUrlParamr } from '@/Helpers/helpers.js';
import Pagination from '@/Components/Pagination.vue';
export default {
	name: 'Home',
  components: {
    Authenticated,
    Head,
		VButton,
		Pagination,
  },
	props: {
		operacoes: Object,
		ativos: Array,
		flash: Object,
		errors: Object,
		filters: Object
	},
	data() {
		return {
			perPage: '',
			page: '',
			params: {
				field: this.filters.field,
				direction: this.filters.direction,
				search: this.filters.search,
			},
		}
	},
	methods: {
		formatMoneyBr,
		formatDateBr,
		getUrlParamr,
		sort(field) {
				this.params.field = field;
				this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
				this.perPage = this.getUrlParamr('perPage');
				this.page = this.getUrlParamr('page');
		},
	},
	watch: {
		params: {
			handler() {
				this.perPage = this.getUrlParamr('perPage');
				this.$inertia.get(route('operacoes.index'), this.params, {replace:true, preserveState: true});
			},
			deep: true,
		},
	},
};
</script>

<style>
</style>