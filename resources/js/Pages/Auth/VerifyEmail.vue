<template>
    <Head title="Email Verification" />

    <div class="mb-4 text-sm text-gray-600">
        Obrigado por inscrever-se! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
    </div>

    <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent" >
        Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.
    </div>

    <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
            <VButton class="bg-gray-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Reenviar email de verificação
            </VButton>

            <Link :href="route('logout')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900">Sair</Link>
        </div>
    </form>
</template>

<script>
import VButton from '@/Components/Button.vue'
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: GuestLayout,

    components: {
        VButton,
        Head,
        Link,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form()
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('verification.send'))
        },
    },

    computed: {
        verificationLinkSent() {
            return this.status === 'verification-link-sent';
        }
    }
}
</script>
