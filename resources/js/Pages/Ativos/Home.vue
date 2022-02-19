<template>
	<Head title="Ativos" />
	
  <Authenticated>
	<template #breadcrumb>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Ativos
		</h2>
    </template>

		<section class="mt-6">
			<div class="bg-white overflow-hidden shadow-md rounded">
				<div class="p-3 bg-white border-b border-gray-200 flex justify-between items-center">
					<div>
						<Link :href="route('ativos.create')" class="px-3 py-2 text-white bg-green-600 hover:bg-green-700 focus:bg-green-700 leading-normal rounded font-semibold text-sm uppercase">
							Novo Ativo
						</Link>
					</div>
					<form>
						<div class="flex w-96">
							<input type="text" v-model="params.search" placeholder="O que deseja buscar?" 
								class="rounded-none rounded-l border-2 border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2">
							<span class="inline-flex items-center px-3 text-sm bg-blue-600 text-white rounded-r border border-l-0 border-blue-600">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</form>
				</div>
			</div>
		</section>

<!-- Section Table -->
	<section class="mt-6">
		<div class="flex flex-col">
			<div class="overflow-x-auto shadow-md sm:rounded">
				<div class="inline-block min-w-full align-middle">
					<div class="overflow-hidden ">
						<table class="min-w-full divide-y divide-gray-200 table-fixed">
							<thead class="bg-gray-200 uppercase">
								<tr>
									<th scope="col" @click="sort('codigo')" class="cursor-pointer p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										<span class="flex flex-row justify-between">
											Ativo
										<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('classe_ativo')" class="cursor-pointer p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										<span class="flex flex-row justify-between">
											Classe Ativo
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('descricao')" class="cursor-pointer p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										<span class="flex flex-row justify-between">
											Descrição
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('setor')" class="cursor-pointer p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										<span class="flex flex-row justify-between">
											Setor/Segmento
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('created_at')" class="cursor-pointer p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										<span class="flex flex-row justify-between">
											Data
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" class="p-3 text-xs font-medium tracking-wider text-left text-gray-700">
										Ações
									</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200">
								<tr v-for="ativo in ativos.data" :key="ativo.id" class="hover:bg-gray-100">
									<td class="py-2 px-4 text-sm font-medium text-gray-900 whitespace-nowrap">
										{{ ativo.codigo }}
									</td>
									<td class="py-2 px-4 text-sm font-medium text-gray-500 whitespace-nowrap">
										{{ ativo.classe_ativo }}
									</td>
									<td class="py-2 px-4 text-sm font-medium text-gray-900 whitespace-nowrap">
										{{ ativo.descricao }}
									</td>
									<td class="py-2 px-4 text-sm font-medium text-gray-900 whitespace-nowrap">
										{{ ativo.setor }}
									</td>	
									<td class="py-2 px-4 text-sm font-medium text-gray-900 whitespace-nowrap">
										{{ formatDateBr(ativo.created_at) }}
									</td>									
									<td class="py-1.5 px-4 text-sm font-medium whitespace-nowrap">
										<div class="btn-group" role="group">
											<Link :href="route('ativos.edit', ativo.id)" class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
												<i class="fas fa-edit"></i>
											</Link>
											<button @click="deleteOperacao(ativo.id)" class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
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
	</section>

	<Pagination :data="ativos" />

	<!-- Notificações -->
	<ToastError :menssagem="flash.error" />
	<ToastSuccess :menssagem="flash.success" />

  </Authenticated>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated.vue';
import { Head, Link } from "@inertiajs/inertia-vue3";
import { formatDateBr, getUrlParamr } from '@/Helpers/helpers.js';
import Pagination from '@/Components/Pagination.vue';
import VButton from "@/Components/Button.vue";
import Alert from "@/Components/Alert.vue";
import ToastError from "@/Components/ToastError.vue";
import ToastSuccess from "@/Components/ToastSuccess.vue";

export default {
  name: 'Home',
	components: {
    Authenticated,
		Head,
		Link,
		Pagination,
		Alert,
		VButton,
		ToastError,
		ToastSuccess
    },
	props: {
		ativos: Object,
		errors: Object,
		flash: Object,
		filters: Object,
	},
	data() {
		return {
			perPage: '',
			page: '',
			isVisibleAlert: Object.keys(this.flash.success ?? '').length > 0,
			params: {
				field: this.filters.field,
				direction: this.filters.direction,
				search: this.filters.search,
			},
		};
	},
	methods: {
		formatDateBr,
		getUrlParamr,
		sort(field) {
			this.params.field = field;
			this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
			this.perPage = this.getUrlParamr('perPage');
			this.page = this.getUrlParamr('page');
		},
		deleteOperacao(id) {
			this.$inertia.delete(this.route('ativos.destroy', {id: id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir esta Operação?'),
			});
		},
	},
	watch: {
		params: {
			handler() {
				this.perPage = this.getUrlParamr('perPage');
				this.$inertia.get(route('ativos.index'), this.params, {replace:true, preserveState: true});
			},
			deep: true,
		},
	},

}
</script>

<style>

</style>