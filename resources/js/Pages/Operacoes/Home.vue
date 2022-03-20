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
			<div class="p-4 bg-white border-b border-gray-200 flex justify-between items-center">
				<div>
					<VButton @click="openModal()" class="bg-green-600 hover:bg-green-700 focus:bg-green-700">
						Novo Aporte
					</VButton>
					<a :href="route('operacoes.export')" class="ml-2 inline-block leading-normal px-3 py-2 rounded font-semibold text-sm text-white uppercase bg-gray-500 hover:bg-gray-700 focus:bg-gray-600">
						Export
					</a>
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
							<thead class="bg-gray-200 uppercase text-xs font-semibold text-gray-600 text-left">
								<tr>
									<th scope="col" @click="sort('codigo_ativo')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Ativo
										<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('tipo_operacao')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Operação
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('classe_ativo')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Classe Ativo
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('quantidade')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Qtd.
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('cotacao_preco')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Cotação
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" class=" tracking-wider p-3">
										<span>
											Valor Total
											<!-- <i class="fas fa-sort ml-3"></i> -->
										</span>
									</th>
									<th scope="col" @click="sort('corretora')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Corretora
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('data_operacao')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Data
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" class="p-3 tracking-wider">
										Ações
									</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-600">
								<tr v-for="operacao in operacoes.data" :key="operacao.id" class="hover:bg-gray-100">
									<td class="py-2 px-4 whitespace-nowrap">
										{{ operacao.codigo_ativo }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										<span :class="operacao.tipo_operacao == 'compra' ? 'bg-green-100 text-green-800' :'bg-red-100 text-red-800'" class="text-xs font-semibold mr-2 px-2 py-0.5 rounded">
											{{ operacao.tipo_operacao}}
										</span>
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ operacao.classe_ativo}}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ numberFormatterBr(operacao.quantidade, 8) }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ formatMoneyBr(operacao.cotacao_preco) }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ formatMoneyBr(operacao.valor_total) }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ operacao.corretora }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ formatDateBr(operacao.data_operacao) }}
									</td>
									<td class="py-1.5 px-4 whitespace-nowrap">
										<div class="btn-group" role="group">
											<button @click="setOperacaoEdit(operacao)" class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
												<i class="fas fa-edit"></i>
											</button>
											<button @click="deleteOperacao(operacao)" class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
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

		<Pagination :data="operacoes" />

		<Modal :isVisible="modalVisible" @close="closeModal()">
			<template #header>{{ titleModal }}</template>
			<template #body>
				<form>
					<div class="mb-4">
						<VLabel for="ativo" value="Ativo:" />
            			<select id="ativo" v-model="form.ativo" required 
							class="mt-1 block w-full justify-items-center border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded shadow-sm">
							<option value="">Selecione um ativo</option>
							<option :value="ativo.id" v-for="ativo in ativos" :key="ativo.id">{{ ativo.codigo }}</option>
						</select>
						<span v-if="form.errors.ativo" class="text-red-600">{{ form.errors.ativo }}</span>
					</div>
					<div class="mb-4">
						<VLabel for="tipo_operacao" value="Tipo Operação:" />
            			<select id="tipo_operacao" v-model="form.tipo_operacao" required 
						 class="mt-1 block w-full justify-items-center border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded shadow-sm">
							<option value="">Selecione tipo de operação...</option>
							<option value="compra">COMPRA</option>
							<option value="venda">VENDA</option>
						</select>
						<span v-if="form.errors.tipo_operacao" class="text-red-600">{{ form.errors.tipo_operacao }}</span>
					</div>
					<div class="mb-4">
						<VLabel for="corretora" value="Corretora:" />
            			<VInput id="corretora" v-model="form.corretora" type="text" class="mt-1 block w-full" required placeholder="Corretora" />
						<span v-if="form.errors.corretora" class="text-red-600">{{ form.errors.corretora }}</span>
					</div>
					<div class="mb-4">
						<VLabel for="data_operacao" value="Data da Operação:" />
            			<VInput id="data_operacao" v-model="form.data_operacao" type="date" class="mt-1 block w-full" required placeholder="Ex.: 00/00/0000" />
						<span v-if="form.errors.data_operacao" class="text-red-600">{{ form.errors.data_operacao }}</span>
					</div>
					
					<div class="mb-4">

						<VLabel for="cotacao" value="Cotação:" />
						<div class="flex mt-1 shadow-sm">
							<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 rounded-l-md border border-r-0 border-gray-300">
								R$
							</span>
							<input type="text" v-model="form.cotacao" id="cotacao" class="block w-full rounded-r-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
								required placeholder="Ex: 1,00, 1000,00 ou 1.000,00">
						</div>
						<span v-if="form.errors.cotacao" class="text-red-600">{{ form.errors.cotacao }}</span>						
					</div>
					<div class="mb-4">
						<VLabel for="quantidade" value="Quantidade:" />
            			<VInput id="quantidade" v-model="form.quantidade" type="text" class="mt-1 block w-full" required placeholder="Ex: 0,00560060 ou 1,50 ou 1000,00" />
						<span v-if="form.errors.quantidade" class="text-red-600">{{ form.errors.quantidade }}</span>
					</div>			
				</form>
			</template>	
			<template #footer>
				<button type="button" v-if="btnModal == 'salvar'" @click="submitFormStore()" class="py-1.5 px-3 rounded bg-green-700 text-white ml-2">Salvar</button>
				<button type="button" v-if="btnModal == 'update'" @click="submitFormUpdate()" class="py-1.5 px-3 rounded bg-green-700 text-white ml-2">Atualizar</button>
			</template>	
		</Modal>

		<ToastSuccess />

  </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import VButton from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import VLabel from "@/Components/Label.vue";
import VInput from "@/Components/Input.vue";
import ToastSuccess from "@/Components/ToastSuccess.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { formatMoneyBr, formatDateBr, numberFormatterBr, getUrlParamr } from '@/Helpers/index.js';
import Pagination from '@/Components/Pagination.vue';
export default {
	name: 'Home',
	components: {
		Authenticated,
		Head,
		VButton,
		Modal,
		Pagination,
		VLabel,
		VInput,
		ToastSuccess
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
			titleModal: 'Novo Aporte',
			btnModal: '',
			modalVisible: false,
			params: {
				field: this.filters.field,
				direction: this.filters.direction,
				search: this.filters.search,
			},
			form: this.$inertia.form({
				id: '',	
				ativo: '',
				tipo_operacao: '',
				corretora: '',
				data_operacao: '',
				cotacao: '',
				quantidade: '',
			}),
		}
	},
	methods: {
		formatMoneyBr,
		formatDateBr,
		numberFormatterBr,
		getUrlParamr,
		sort(field) {
				this.params.field = field;
				this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
				this.perPage = this.getUrlParamr('perPage');
				this.page = this.getUrlParamr('page');
		},
		setOperacaoEdit(operacao) {
			// Prepara o formulário para edição
			this.form.id = operacao.id;
			this.form.ativo = operacao.ativo_id;
			this.form.tipo_operacao = operacao.tipo_operacao;
			this.form.corretora = operacao.corretora;
			this.form.data_operacao = operacao.data_operacao.split('T')[0];
			this.form.cotacao = this.numberFormatterBr(operacao.cotacao_preco, 4);
			this.form.quantidade = this.numberFormatterBr(operacao.quantidade, 8);
			// Abre o modal
			this.titleModal = 'Editar Operação';
			this.btnModal = 'update';
			this.modalVisible = true;

		},
		submitFormStore() {
			this.form.post(route('operacoes.store'), {
				onSuccess: () => {
					this.form.reset();
				},
			});
		},
		submitFormUpdate() {
			this.form.put(this.route('operacoes.update'), { 
				preserveState:true,
				onSuccess: () => {
					this.form.reset();
					this.modalVisible = false;
				},
			});
		},
		deleteOperacao(operacao) {
			this.$inertia.delete(this.route('operacoes.destroy', {id: operacao.id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir esta Operação?'),
			});
		},
		openModal() {
			this.titleModal = 'Novo Aporte';
			this.btnModal = 'salvar';
			this.modalVisible = true;
		},
		closeModal() {
			this.form.reset();
			this.form.clearErrors();
			this.modalVisible = false;
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