<template>
    <div class="tab-pane fade" :id="id" role="tabpanel" aria-labelledby="tabs-ativo">
        <div class="mb-1 text-gray-700 text-lg font-semibold">
            <h2>
                Selecione um ativo e percentual(%) Meta/Objetivo
            </h2>
        </div>
        <div class="p-3 bg-gray-100 rounded-lg">
            <form @submit.prevent="classeRebalanceamentoStore()">
                <div class="flex flex-col sm:flex-row">
                    <div class="w-full mr-3 mb-3">
                        <label for="select_ativo" class="block mb-1 text-sm font-medium text-gray-900">Ativo:</label>
                        <select id="select_ativo" v-model="ativoRebalanceamentoForm.ativo_id" required 
                            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                            :class="ativoRebalanceamentoForm.errors.ativo_id ? 'border-red-600' : 'border-gray-300'">
                            <option value="">Selecione um ativo</option>
                            <option v-for="ativo in ativos" :key="ativo.id" :value="ativo.id">{{ ativo.codigo }}</option>
                        </select>
                        <span v-if="ativoRebalanceamentoForm.errors.ativo_id" class="text-red-600">
                            {{ ativoRebalanceamentoForm.errors.ativo_id }}
                        </span>
                    </div>
                    <div class="w-full mr-3 mb-3">
                        <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                        <input type="text" v-model="ativoRebalanceamentoForm.percentual" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15,00" required>
                        <span v-if="ativoRebalanceamentoForm.errors.percentual" class="text-red-600">
                            {{ ativoRebalanceamentoForm.errors.percentual }}
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
                                        Ativo
                                    </th>
                                    <th scope="col" class="p-3 tracking-wider text-left uppercase">
                                        Nome
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
                                <tr v-for="ativoRebalanceamento in ativoRebalanceamentos" :key="ativoRebalanceamento.id" class="hover:bg-gray-100">
                                    <td class="py-2 px-4 whitespace-nowrap">{{ ativoRebalanceamento.ativo.codigo }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap">{{ ativoRebalanceamento.ativo.nome }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap">{{ ativoRebalanceamento.percentual }} %</td>
                                    <td class="py-2 px-4 text-sm font-medium whitespace-nowrap">
                                        <button @click="editRebalaceamentoAtivo(ativoRebalanceamento)" class="mr-1 inline-block py-2 px-2.5 text-white bg-yellow-400 hover:bg-yellow-500 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteRebalaceamentoAtivo(ativoRebalanceamento.id)" class="inline-block px-2.5 py-2 text-white bg-red-600 hover:bg-red-700 font-medium text-xs leading-tight rounded shadow-md focus:ring-0">
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
        <template #header>Editar Rebalanceamento por Ativo</template>
        <template #body>
            <form class="flex flex-col">
                <div class="w-full mr-3 mb-3">
                    <label for="select_classe_ativo" class="block mb-1 text-sm font-medium text-gray-900">Ativo:</label>
                    <select id="select_classe_ativo" v-model="ativoRebalanceamentoFormUpdate.ativo_id" required 
                        class="bg-gray-50 border-2 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        :class="ativoRebalanceamentoFormUpdate.errors.ativo_id ? 'border-red-600' : 'border-gray-300'">
                        <option value="">Selecione um ativo</option>
                        <option v-for="ativo in ativos" :key="ativo.id" :value="ativo.id">{{ ativo.codigo }}</option>
                    </select>
                    <span v-if="ativoRebalanceamentoFormUpdate.errors.ativo_id" class="text-red-600">
                        {{ ativoRebalanceamentoFormUpdate.errors.ativo_id }}
                    </span>
                </div>
                <div class="w-full mr-3 mb-3">
                    <label for="meta_objetivo" class="block mb-1 text-sm font-medium text-gray-900">% Meta/Objetivo:</label>
                    <input type="text" v-model="ativoRebalanceamentoFormUpdate.percentual" id="meta_objetivo" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ex: 15,00" required>
                </div>
            </form>
        </template>	
        <template #footer>
            <button @click="ativoRebalanceamentoUpdate()" type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded text-sm sm:w-auto px-3 py-2 ml-2 text-center">
                Atualizar
            </button>
        </template>	
    </Modal>

    <ToastError />

</template>

<script>
import Modal from "@/Components/Modal.vue";
import ToastError from "@/Components/ToastError.vue";

export default {
    name: 'RebalanceamentoAtivo',
    props: {
        id: {
            type: String,
            default: 'tab_ativo',
        },
        ativoRebalanceamentos: Object,
        ativos: Object,
    },
    components: {
        Modal,
        ToastError,
    },
    data() {
        return {
            modalVisible: false,
            ativoRebalanceamentoForm: this.$inertia.form({
                ativo_id: '',
                percentual: '',
            }),
            ativoRebalanceamentoFormUpdate: this.$inertia.form({
                id: '',
                ativo_id: '',
                percentual: '',
            }),
        }
    },
    methods: {
        classeRebalanceamentoStore() {
            this.ativoRebalanceamentoForm.post(route('rebalanceamento.percentualAtivoStore'), {
                onSuccess: () => {
					this.ativoRebalanceamentoForm.reset();
				},
            });
        },
        editRebalaceamentoAtivo(data) {
            this.ativoRebalanceamentoFormUpdate.id = data.id;
            this.ativoRebalanceamentoFormUpdate.ativo_id = data.ativo_id;
            this.ativoRebalanceamentoFormUpdate.percentual = data.percentual;
            this.modalVisible = true;            
        },
        ativoRebalanceamentoUpdate() {
            this.ativoRebalanceamentoFormUpdate.put(route('rebalanceamento.percentualAtivoUpdate'), {
                onSuccess: () => {
                    this.ativoRebalanceamentoFormUpdate.reset();
                    this.modalVisible = false;
                }
            });
        },
        deleteRebalaceamentoAtivo(id) {
            this.$inertia.delete(this.route('rebalanceamento.percentualAtivoDestroy', {id: id}), {
				preserveState: true,
				onBefore: () => confirm('Tem certeza que deseja excluir este rebalanceamento?'),
			});
        },
        closeModal() {
			this.ativoRebalanceamentoFormUpdate.reset();
			this.ativoRebalanceamentoFormUpdate.clearErrors();
			this.modalVisible = false;
		},
    },
}
</script>