<template>
  <Head title="Editar Ativo" />
  <Authenticated>
    <template #breadcrumb>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Editar Ativo
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
              />
              <span v-if="form.errors.codigo" class="text-red-600">{{
                form.errors.codigo
              }}</span>
            </div>
            <div class="mb-4">
              <VLabel for="descricao" value="Descrição:" />
              <VInput
                v-model="form.descricao"
                id="descricao"
                type="text"
                class="mt-1 block w-full"
                required
              />
              <span v-if="form.errors.descricao" class="text-red-600">{{
                form.errors.descricao
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
                Atualizar
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
  name: "Edit",
  components: {
    Authenticated,
    Head,
    VLabel,
    VInput,
    Link,
    VButton,
  },
  props: {
    ativo: Object,
    classesAtivos: Object,
    errors: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.ativo.id,
        codigo: this.ativo.codigo,
        descricao: this.ativo.descricao,
        setor: this.ativo.setor,
        classe_ativo: this.ativo.classe_ativo_id,
      }),
    };
  },
  methods: {
    submitForm() {
      this.form.put(route('ativos.update'));
    },
  },
};
</script>

<style>
</style>