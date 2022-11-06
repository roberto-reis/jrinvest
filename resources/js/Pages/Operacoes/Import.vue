<template>
  <Head title="Importar Operações" />
  <Authenticated>
    <template #breadcrumb>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Importar Operações
      </h2>
    </template>
    <section class="mt-6">
      <div class="overflow-hidden flex justify-center">
        <div class="p-3 bg-white border-b border-gray-200 flex justify-center bg-white w-full sm:w-2/4 shadow-md rounded">
          <form class="w-full" @submit.prevent="submit()">
                <div class="flex items-center flex-col">

                    <div class="mb-1">
                      <Alert :menssagem="flash.success" class="bg-green-100 text-green-700" />
                    </div>

                    <div class="mb-4">
                        <label for="formFileLg" class="form-label inline-block mb-2 text-gray-700">
                            Selecione Um arquivo, ex: .xlsx ou .xls
                        </label>
                        <a :href="route('operacoes.import-modelo')" class="text-blue-700 pl-2">(veja modelo)</a>
                        <input type="file" @input="form.arquivo = $event.target.files[0]" id="formFileLg" class="form-control block w-full px-2 py-1.5 text-xl font-normal  text-gray-700
                            bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0
                            focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" accept=".xlsx, .xls">
                        <span v-if="errors.arquivo" class="text-red-600">{{ errors.arquivo }}</span>
                    </div>

                    <div class="flex justify-start">
                        <a :href="route('operacoes.index')" :disabled="form.processing" class="inline-block leading-normal px-3 py-2 rounded font-semibold text-sm uppercase bg-red-600 text-white hover:bg-red-700">
						    Voltar
					    </a>
                        <VButton type="submit" :disabled="form.processing" class="ml-2 bg-green-600 hover:bg-green-700 px-10">
                          {{ form.progress ? 'Enviando '+form.progress.percentage+'%' : 'Enviar' }}
                        </VButton>
                    </div>
                </div>


            </form>
        </div>
      </div>
    </section>
  </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { useForm } from '@inertiajs/inertia-vue3'
import VButton from "@/Components/Button.vue";
import Alert from "@/Components/Alert.vue";

export default {
  name: "Import",
  components: {
      Authenticated,
      Head,
      VButton,
      Alert,
  },
  props: {
		errors: Object,
    flash: Object,
	},
  methods: {

  },
  setup () {
    const form = useForm({
      arquivo: null,
    })

    function submit() {
      form.post(route('operacoes.import.store'), { preserveState: false});
    }

    return { form, submit }
  },
}
</script>
