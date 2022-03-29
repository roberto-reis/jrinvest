<template>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600">
        Esta é uma área segura do aplicativo. Por favor, confirme sua senha antes de continuar.
    </div>

    <ValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <VLabel for="password" value="Senha" />
            <VInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" autofocus />
        </div>

        <div class="flex justify-end mt-4">
            <VButton class="ml-4 bg-gray-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Confirme
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

    data() {
        return {
            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.confirm'), {
                onFinish: () => this.form.reset(),
            })
        }
    }
}
</script>
