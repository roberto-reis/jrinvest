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
						<VButton @click="modalVisible = true" class="bg-green-600 hover:bg-green-700 focus:bg-green-700">
							Novo Aporte
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
												<th scope="col" @click="sort('codigo_ativo')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Ativo
														<i class="fas fa-sort ml-3"></i>
													</span>
												</th>
												<th scope="col" @click="sort('tipo_operacao')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Operação
														<i class="fas fa-sort ml-3"></i>
													</span>
												</th>
												<th scope="col" @click="sort('classe_ativo')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between w-28">
														Classe Ativo
														<i class="fas fa-sort ml-3"></i>
													</span>
												</th>
												<th scope="col" @click="sort('quantidade')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Qtd.
														<i class="fas fa-sort ml-3"></i>
														</span>
												</th>
												<th scope="col" @click="sort('cotacao_preco')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Cotação
														<i class="fas fa-sort ml-3"></i>
													</span>
												</th>
												<th scope="col" @click="sort('valor_total')" class="px-2 py-2">
													<span>Valor Total</span>
													<!-- <i class="fas fa-sort ml-3"></i> -->
												</th>
												<th scope="col" @click="sort('corretora')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Corretora
														<i class="fas fa-sort ml-3"></i>
													</span>
												</th>
												<th scope="col" @click="sort('created_at')" class="px-2 py-2">
													<span class="cursor-pointer flex flex-row justify-between">
														Data
														<i class="fas fa-sort ml-3"></i>
													</span>
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
													<span :class="operacao.tipo_operacao == 'compra' ? 'bg-green-600' :'bg-red-600'" class="text-xs inline-block py-1 px-1.5 leading-none text-center whitespace-nowrap align-baseline font-bold text-white rounded">
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
														<button type="button" @click="deleteOperacao(operacao)" class="inline-block ml-1 px-2.5 py-2 bg-red-600 text-white font-medium text-xs leading-tight rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">
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

		<Modal :isVisible="modalVisible" @close="closeModal()">
			<template #header>{{ titleModal }}</template>
			<template #body>
				<Alert v-if="alertVisible">
					{{ flash.success }}
				</Alert>
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
            			<VInput id="cotacao" v-model="form.cotacao" type="text" class="mt-1 block w-full" required placeholder="Ex.: 25,00" />
						<span v-if="form.errors.cotacao" class="text-red-600">{{ form.errors.cotacao }}</span>
					</div>
					<div class="mb-4">
						<VLabel for="quantidade" value="Quantidade:" />
            			<VInput id="quantidade" v-model="form.quantidade" type="text" class="mt-1 block w-full" required placeholder="Ex.: 0,00110011" />
						<span v-if="form.errors.quantidade" class="text-red-600">{{ form.errors.quantidade }}</span>
					</div>			
				</form>
			</template>	
			<template #footer>
				<button type="button" v-if="btnModal == 'salvar'" @click="submitFormStore()" class="py-1.5 px-3 rounded bg-green-700 text-white ml-2">Salvar</button>
				<button type="button" v-if="btnModal == 'update'" @click="submitFormUpdate()" class="py-1.5 px-3 rounded bg-green-700 text-white ml-2">Atualizar</button>
			</template>	
		</Modal>

  </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import VButton from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import VLabel from "@/Components/Label.vue";
import VInput from "@/Components/Input.vue";
import Alert from "@/Components/Alert.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { formatMoneyBr, formatDateBr, getUrlParamr } from '@/Helpers/helpers.js';
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
		Alert,
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
			modalVisible: false,
			btnModal: 'salvar',
			alertVisible: false,
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
		getUrlParamr,
		sort(field) {
				this.params.field = field;
				this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
				this.perPage = this.getUrlParamr('perPage');
				this.page = this.getUrlParamr('page');
		},
		submitFormStore() {
			this.form.post(route('operacoes.store'), {
				onSuccess: () => {
					this.form.reset();
					this.alertVisible = true;
				},
			});
			setTimeout(()=> {
				this.alertVisible = false
			}, 3000)
		},
		setOperacaoEdit(operacao) {
			// Prepara o formulário para edição
			this.form.id = operacao.id;
			this.form.ativo = operacao.ativo_id;
			this.form.tipo_operacao = operacao.tipo_operacao;
			this.form.corretora = operacao.corretora;
			this.form.data_operacao = operacao.created_at.split('T')[0];
			this.form.cotacao = operacao.cotacao_preco;
			this.form.quantidade = operacao.quantidade;
			// Abre o modal
			this.titleModal = 'Editar Operação';
			this.btnModal = 'update';
			this.modalVisible = true;

		},
		submitFormUpdate() {
			this.form.put(this.route('operacoes.update'), { 
				preserveState:true,
				onSuccess: () => this.alertVisible = true,
			});
			setTimeout(()=> {
				this.alertVisible = false
			}, 3000);
		},
		deleteOperacao(operacao) {
			this.$inertia.delete(this.route('operacoes.destroy', {id: operacao.id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir esta Operação?'),
			});
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