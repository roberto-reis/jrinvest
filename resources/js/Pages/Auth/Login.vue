<template>
    <Head title="Login" />

    <ValidationErrors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <VLabel for="email" value="Email:" />
            <VInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4">
            <VLabel for="password" value="Senha:" />
            <VInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="ml-2 text-sm text-gray-600">Lembre de mim</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Esqueceu sua senha?
            </Link>

            <VButton class="ml-4 bg-gray-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Login
            </VButton>
        </div>
    </form>
</template>

<script>
import VButton from '@/Components/Button.vue'
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/Guest.vue'
import VInput from '@/Components/Input.vue'
import VLabel from '@/Components/Label.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: GuestLayout,

    components: {
        VButton,
        Checkbox,
        VInput,
        VLabel,
        ValidationErrors,
        Head,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}
</script>
