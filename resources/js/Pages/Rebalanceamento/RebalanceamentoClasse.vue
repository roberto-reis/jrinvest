<template>
    <div class="tab-pane fade show active" :id="id" role="tabpanel" aria-labelledby="tabs-classe-ativo">
        <div class="mb-1">
            <h2 class="text-gray-700 text-lg font-semibold">
                Selecione uma classe de um ativo e percentual(%) Meta/Objetivo
            </h2>
        </div>

        <div class="p-3 bg-gray-100 rounded-lg">
            <form @submit.prevent="classeRebalanceamentoStore()">
                <div class="flex flex-col sm:flex-row">
                    <div class="w-full mr-3 mb-3">
                        <label for="select_classe_ativo" class="block mb-1 text-sm font-medium text-gray-900">Classe de Ativo:</label>
                        <select id="select_classe_ativo" v-model="classeRebalanceamentoForm.classe_ativo_id" required 
                            class="bg-gray-50 border-2 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                            :class="classeRebalanceamentoForm.errors.classe_ativo_id ? 'border-red-600' : 'border-gray-300'">
                            <option value="">Selecione um ativo</option>
                            <option v-for="classeAtivo in classeAtivos" :key="classeAtivo.id" :value="classeAtivo.id">{{ classeAtivo.nome }}</option>
                        </select>
                        <span v-if="classeRebalanceamentoForm.errors.classe_ativo_id" class="text-red-600">
                            {{ classeRebalanceamentoForm.errors.classe_ativo_id }}
                        </span>
                    </div>
                    <div class="w-full mr-3 mb-3">
                        <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                        <input type="text" v-model="classeRebalanceamentoForm.percentual" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15.00"
                            :class="classeRebalanceamentoForm.errors.classe_ativo_id ? 'border-red-600' : 'border-gray-300'" required>
                        <span v-if="classeRebalanceamentoForm.errors.percentual" class="text-red-600">
                            {{ classeRebalanceamentoForm.errors.percentual }}
                        </span>
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
                        <table class="min-w-full table-fixed">
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
                            <tbody class="bg-white text-sm font-medium text-gray-900">
                                <tr v-for="classeRebalanceamento in classeRebalanceamentos" :key="classeRebalanceamento.id" class="hover:bg-gray-100">
                                    <td class="py-2 px-4 whitespace-nowrap">{{ classeRebalanceamento.classe_ativo.nome }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap">{{ classeRebalanceamento.classe_ativo.descricao }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap">{{ classeRebalanceamento.percentual }} %</td>
                                    <td class="py-2 px-4 text-sm font-medium whitespace-nowrap">
                                        <button @click="editRebalaceamentoClasse(classeRebalanceamento)" class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteRebalaceamentoClasse(classeRebalanceamento.id)" class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
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

    <Modal :isVisible="modalVisible" @close="closeModal()">
        <template #header>Editar Classe de Ativo</template>
        <template #body>
            <form class="flex flex-col">
                <div class="w-full mr-3 mb-3">
                    <label for="select_classe_ativo" class="block mb-1 text-sm font-medium text-gray-900">Classe de Ativo:</label>
                    <select id="select_classe_ativo" v-model="classeRebalanceamentoFormUpdate.classe_ativo_id" required 
                        class="bg-gray-50 border-2 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        :class="classeRebalanceamentoFormUpdate.errors.classe_ativo_id ? 'border-red-600' : 'border-gray-300'">
                        <option value="">Selecione um ativo</option>
                        <option v-for="classeAtivo in classeAtivos" :key="classeAtivo.id" :value="classeAtivo.id">{{ classeAtivo.nome }}</option>
                    </select>
                    <span v-if="classeRebalanceamentoFormUpdate.errors.classe_ativo_id" class="text-red-600">
                        {{ classeRebalanceamentoFormUpdate.errors.classe_ativo_id }}
                    </span>
                </div>
                <div class="w-full mr-3 mb-3">
                    <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                    <input type="text" v-model="classeRebalanceamentoFormUpdate.percentual" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15,00" required>
                </div>
            </form>
        </template>	
        <template #footer>
            <button @click="classeRebalanceamentoUpdate()" type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded text-sm sm:w-auto px-3 py-2 ml-2 text-center">
                Atualizar
            </button>
        </template>	
    </Modal>

</template>

<script>
import Modal from "@/Components/Modal.vue";

export default {
    name: 'RebalanceamentoClasse',
    props: {
        classeRebalanceamentos: Object,
        classeAtivos: Object,
        id: String,
    },
    components: {
        Modal,
    },
    data() {
        return {
            modalVisible: false,
            classeRebalanceamentoForm: this.$inertia.form({
                classe_ativo_id: '',
                percentual: '',
            }),
            classeRebalanceamentoFormUpdate: this.$inertia.form({
                id: '',
                classe_ativo_id: '',
                percentual: '',
            }),
        };
    },
    methods: {
        classeRebalanceamentoStore() {
            this.classeRebalanceamentoForm.post(route('rebalanceamento.percentualClasseStore'), {
                onSuccess: () => {
					this.classeRebalanceamentoForm.reset();
				},
            });
        },
        editRebalaceamentoClasse(data) {
            this.classeRebalanceamentoFormUpdate.id = data.id;
            this.classeRebalanceamentoFormUpdate.classe_ativo_id = data.classe_ativo_id;
            this.classeRebalanceamentoFormUpdate.percentual = data.percentual;
            this.modalVisible = true;            
        },
        classeRebalanceamentoUpdate() {
            this.classeRebalanceamentoFormUpdate.put(route('rebalanceamento.percentualClasseUpdate'), {
                onSuccess: () => {
                    this.classeRebalanceamentoFormUpdate.reset();
                    this.modalVisible = false;
                }
            });
        },
        deleteRebalaceamentoClasse(id) {
            this.$inertia.delete(this.route('rebalanceamento.percentualClasseDestroy', {id: id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir este rebalanceamento?'),
			});
        },
        closeModal() {
			this.classeRebalanceamentoFormUpdate.reset();
			this.classeRebalanceamentoFormUpdate.clearErrors();
			this.modalVisible = false;
		},
    }
};
</script>