<template>
    <Head title="Register" />

    <ValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <Label for="name" value="Nome:" />
            <Input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
        </div>

        <div class="mt-4">
            <Label for="last_name" value="Sobrenome:" />
            <Input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name" />
        </div>

        <div class="mt-4">
            <Label for="phone" value="Telefone:" />
            <Input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus autocomplete="phone" />
        </div>

        <div class="mt-4">
            <Label for="email" value="Email:" />
            <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
        </div>

        <div class="mt-4">
            <Label for="password" value="Senha:" />
            <Input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <Label for="password_confirmation" value="Confirme a senha:" />
            <Input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Já é registrado?
            </Link>

            <Button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Registrar
            </Button>
        </div>
    </form>
</template>

<script>
import Button from '@/Components/Button.vue'
import GuestLayout from '@/Layouts/Guest.vue'
import Input from '@/Components/Input.vue'
import Label from '@/Components/Label.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: GuestLayout,

    components: {
        Button,
        Input,
        Label,
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
