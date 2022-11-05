<template>
    <Head title="Forgot Password" />

    <div class="mb-4 text-sm text-gray-600">
        Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e nós lhe enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <ValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <VLabel for="email" value="Email" />
            <VInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <VButton class="bg-gray-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Link de redefinição de senha de e-mail
            </VButton>
        </div>
    </form>
</template>

<script>
import VButton from '@/Components/Button.vue'
import GuestLayout from '@/Layouts/Guest.vue'
import VInput from '@/Components/Input.vue'
import VLabel from '@/Components/Label.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    layout: GuestLayout,

    components: {
        VButton,
        VInput,
        VLabel,
        ValidationErrors,
        Head,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: ''
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
}
</script>
