<template>
    <Head title="Reset Password" />

    <ValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <VLabel for="email" value="Email:" />
            <VInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4">
            <VLabel for="password" value="Senha" />
            <VInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <VLabel for="password_confirmation" value="Confirme a Senha:" />
            <VInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <VButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Redefinir senha
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
        email: String,
        token: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.update'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
