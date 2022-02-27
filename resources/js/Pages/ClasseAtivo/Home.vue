<template>
	<Head title="Ativos" />
	
  <Authenticated>
	<template #breadcrumb>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			Classe de Ativos
		</h2>
    </template>

		<section class="mt-6">
			<div class="bg-white overflow-hidden shadow-md rounded">
				<div class="p-3 bg-white border-b border-gray-200 flex justify-between items-center">
					<div>
						<form class="flex" @submit.prevent="submitFormCreate()">
                            <div class="mr-2">
                                <div class="relative z-0 w-full group">
                                    <input v-model="formCreate.nome" type="text" class="block rounded pt-4 pb-1 px-2 w-full text-sm text-gray-900 bg-transparent border-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="floating_email" class="absolute text-sm text-gray-500 duration-300 transform scale-75 top-4 left-2 -z-10 origin-[0] peer-focus:left-1 peer-placeholder-shown:scale-100 -translate-y-4 peer-focus:-translate-y-4 peer-placeholder-shown:translate-y-0 peer-focus:scale-75">
                                        Classe de Ativo
                                    </label>
                                </div>
                                <span v-if="formCreate.errors.nome" class="text-red-600">{{ formCreate.errors.nome }}</span>
                            </div>
                            <div class="mr-2 w-64">
                                <div class="relative z-0 w-full group">
                                    <input v-model="formCreate.descricao" type="text" class="block rounded pt-4 pb-1 px-2 w-full text-sm text-gray-900 bg-transparent border-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="floating_email" class="absolute text-sm text-gray-500 duration-300 transform scale-75 top-4 left-2 -z-10 origin-[0] peer-focus:left-1 peer-placeholder-shown:scale-100 -translate-y-4 peer-focus:-translate-y-4 peer-placeholder-shown:translate-y-0 peer-focus:scale-75">
                                        Descrição
                                    </label>
                                </div>
                                <span v-if="formCreate.errors.descricao" class="text-red-600">{{ formCreate.errors.descricao }}</span>
                            </div>
                            <div>
                                <VButton class="border-2 border-blue-500 bg-blue-500 hover:bg-blue-700 text-whited">
                                    Cadastrar
                                </VButton>
                            </div>
                        </form>
					</div>
					<form>
						<div class="flex w-96">
							<input v-model="params.search" placeholder="O que deseja buscar?" 
								class="rounded-none rounded-l border-2 border-gray-300 text-gray-900 focus:outline-none focus:ring-0 focus:border-blue-600 block flex-1 min-w-0 w-full text-sm p-2">
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
							<thead class="bg-gray-200 uppercase text-xs text-gray-600 font-semibold text-left">
								<tr>
									<th scope="col" @click="sort('nome')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Classe de Ativo
										<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('descricao')" class="cursor-pointer p-3 tracking-wider">
										<span class="flex flex-row justify-between">
											Descrição
											<i class="fas fa-sort ml-3"></i>
										</span>
									</th>
									<th scope="col" @click="sort('created_at')" class="cursor-pointer p-3 tracking-wider">
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
							<tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-500">
								<tr v-for="classe in classesAtivos.data" :key="classe.id" class="hover:bg-gray-100">
									<td class="py-2 px-4 whitespace-nowrap">
										{{ classe.nome }}
									</td>
									<td class="py-2 px-4 whitespace-nowrap">
										{{ classe.descricao }}
									</td>	
									<td class="py-2 px-4 whitespace-nowrap">
										{{ formatDateBr(classe.created_at) }}
									</td>									
									<td class="py-1.5 px-4 whitespace-nowrap">
										<div class="btn-group" role="group">
											<button @click="setClasseAtivoEdit(classe)" class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
												<i class="fas fa-edit"></i>
											</button>
											<button @click="deleteOperacao(classe.id)" class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
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

	<Pagination :data="classesAtivos" />

	<ToastError />
	<ToastSuccess />

	<Modal :isVisible="modalVisible" @close="closeModal()">
		<template #header>Editar Classe de Ativo</template>
		<template #body>
			<form class="flex flex-col">
				<div class="mb-4">
					<VLabel for="nome" value="Classe de Ativo:" />
					<VInput id="nome" v-model="formUpdate.nome" type="text" class="mt-1 block w-full" required />
					<span v-if="formUpdate.errors.nome" class="text-red-600">{{ formUpdate.errors.nome }}</span>
				</div>
				<div class="mb-4">
					<VLabel for="descricao" value="Descrição:" />
					<VInput id="descricao" v-model="formUpdate.descricao" type="text" class="mt-1 block w-full" required />
					<span v-if="formUpdate.errors.descricao" class="text-red-600">{{ formUpdate.errors.descricao }}</span>
				</div>	
			</form>
		</template>	
		<template #footer>
			<VButton @click="submitFormUpdate()" class="py-2 px-3 rounded bg-blue-500 hover:bg-blue-700 text-white ml-2">
				Atualizar
			</VButton>
		</template>	
	</Modal>


  </Authenticated>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated.vue';
import { Head, Link } from "@inertiajs/inertia-vue3";
import { formatDateBr, getUrlParamr } from '@/Helpers/helpers.js';
import Pagination from '@/Components/Pagination.vue';
import Modal from "@/Components/Modal.vue";
import VButton from "@/Components/Button.vue";
import VLabel from "@/Components/Label.vue";
import VInput from "@/Components/Input.vue";
import ToastError from "@/Components/ToastError.vue";
import ToastSuccess from "@/Components/ToastSuccess.vue";
export default {
    name: 'Home',
	components: {
        Authenticated,
        Head,
        Link,
        VButton,
        VLabel,
        VInput,
		Modal,
		Pagination,
		ToastError,
		ToastSuccess
    },
	props: {
        classesAtivos: Object,
		errors: Object,
		flash: Object,
        filters: Object,
	},
	data() {
		return {
			perPage: '',
			page: '',
			btnForm: 'salvar',
			modalVisible: false,
			params: {
				field: this.filters.field,
				direction: this.filters.direction,
				search: this.filters.search,
			},
            formCreate: this.$inertia.form({
                nome: "",
                descricao: "",
            }),
			formUpdate: this.$inertia.form({
				id: '',
                nome: "",
                descricao: "",
            }),
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
		setClasseAtivoEdit(classAtivo) {
			// Prepara o formulário para edição
			this.formUpdate.id = classAtivo.id;
			this.formUpdate.nome = classAtivo.nome;
			this.formUpdate.descricao = classAtivo.descricao;
			// Abre o modal
			this.modalVisible = true;
		},
        submitFormCreate() {
            this.formCreate.post(route('classe_ativo.store'), {
                onSuccess: () => {
					this.formCreate.reset();
				},
            });
        },
        submitFormUpdate() {
            this.formUpdate.put(route('classe_ativo.update'), {
				onSuccess: () => { 
					this.modalVisible = false;
				}
			});
        },
		deleteOperacao(id) {
			this.$inertia.delete(this.route('classe_ativo.destroy', {id: id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir esta Operação?'),
			});
		},
		closeModal() {
			this.formUpdate.reset();
			this.formUpdate.clearErrors();
			this.modalVisible = false;
		},
	},
	computed: {
		msgError() {
			return this.flash.error;
		}
	},
	watch: {
		params: {
			handler() {
				this.perPage = this.getUrlParamr('perPage');
				this.$inertia.get(route('classe_ativo.index'), this.params, {replace:true, preserveState: true});
			},
			deep: true,
		},

	},

}
</script>

<style>

</style>