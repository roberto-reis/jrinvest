<template>
  <Head title="Novo Ativo" />
  <Authenticated>
    <template #breadcrumb>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Novo Ativo
      </h2>
    </template>
    <section class="mt-6">
      <div class="overflow-hidden flex justify-center">
        <div
          class="
            p-3
            bg-white
            border-b border-gray-200
            flex
            justify-center
            bg-white
            w-full
            sm:w-2/4
            shadow-md
            rounded
          "
        >
          <form class="w-full" @submit.prevent="submitForm()">
            <div class="mb-4">
              <VLabel for="codigo" value="Codigo do Ativo:" />
              <VInput
                v-model="form.codigo"
                id="codigo"
                type="text"
                class="mt-1 block w-full"
                required
                placeholder="Ex: BTC ou MXRF11"
              />
              <span v-if="form.errors.codigo" class="text-red-600">{{
                form.errors.codigo
              }}</span>
            </div>
            <div class="mb-4">
              <VLabel for="nome" value="Nome do Ativo:" />
              <VInput
                v-model="form.nome"
                id="nome"
                type="text"
                class="mt-1 block w-full"
                required
                placeholder="Ex: Bitcoin ou MAXI RENDA FDO INV IMOB "
              />
              <span v-if="form.errors.nome" class="text-red-600">{{
                form.errors.nome
              }}</span>
            </div>
            <div class="mb-4">
              <VLabel for="setor" value="Setor:" />
              <VInput
                v-model="form.setor"
                id="quantidade"
                type="text"
                class="mt-1 block w-full"
                required
                placeholder="Ex: Blokchain ou Papel Segmento Híbrido Gestão Ativa"
              />
              <span v-if="form.errors.setor" class="text-red-600">{{
                form.errors.setor
              }}</span>
            </div>
            <div class="mb-4">
              <VLabel for="classe_ativo" value="Classe do Ativo:" />
              <select
                v-model="form.classe_ativo"
                id="classe_ativo"
                required
                class="
                  mt-1
                  block
                  w-full
                  justify-items-center
                  border-gray-300
                  focus:border-indigo-300
                  focus:ring
                  focus:ring-indigo-200
                  focus:ring-opacity-50
                  rounded
                  shadow-sm
                "
              >
                <option value="">Selecione classe do ativo</option>
                <option
                  :value="classeAtivo.id"
                  v-for="classeAtivo in classesAtivos"
                  :key="classeAtivo.id"
                >
                  {{ classeAtivo.nome }}
                </option>
              </select>
              <span v-if="form.errors.classe_ativo" class="text-red-600">{{
                form.errors.classe_ativo
              }}</span>
            </div>
            <div class="flex justify-end">
              <Link
                :href="route('ativos.index')"
                class="
                  px-3
                  py-2
                  mr-2
                  text-white
                  bg-red-600
                  hover:bg-red-700
                  leading-normal
                  rounded
                  font-semibold
                  text-sm
                  uppercase
                "
              >
                Cancelar
              </Link>
              <VButton
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-whited"
              >
                Salvar
              </VButton>
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
import VButton from "@/Components/Button.vue";
import VLabel from "@/Components/Label.vue";
import VInput from "@/Components/Input.vue";
export default {
  name: "Create",
  components: {
    Authenticated,
    Head,
    VLabel,
    VInput,
    Link,
    VButton,
  },
  props: {
    classesAtivos: Object,
    errors: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        codigo: "",
        nome: "",
        setor: "",
        classe_ativo: "",
      }),
    };
  },
  methods: {
    submitForm() {
      this.form.post(route('ativos.store'));
    },
  },
};
</script>

<style>
</style>