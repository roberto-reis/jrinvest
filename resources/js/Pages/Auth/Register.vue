<template>
    <Head title="Register" />

    <ValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <VLabel for="name" value="Nome:" />
            <VInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
        </div>

        <div class="mt-4">
            <VLabel for="last_name" value="Sobrenome:" />
            <VInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name" />
        </div>

        <div class="mt-4">
            <VLabel for="phone" value="Telefone:" />
            <VInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus autocomplete="phone" />
        </div>

        <div class="mt-4">
            <VLabel for="email" value="Email:" />
            <VInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
        </div>

        <div class="mt-4">
            <VLabel for="password" value="Senha:" />
            <VInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <VLabel for="password_confirmation" value="Confirme a senha:" />
            <VInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Já é registrado?
            </Link>

            <VButton class="ml-4 bg-gray-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Registrar
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
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: GuestLayout,

    components: {
        VButton,
        VInput,
        VLabel,
        ValidationErrors,
        Head,
        Link,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                last_name: '',
                phone: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false,
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
