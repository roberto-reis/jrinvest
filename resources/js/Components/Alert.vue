<template>
    <div v-if="isVisible" class="absolute top-16 right-0 w-96 mr-2 z-50 shadow-md border rounded-lg py-3 px-4 mb-3 text-base inline-flex justify-between items-center">
        {{ menssagem }}
        <button type="button" @click="isVisible = false" class="-mr-2 px-1.5 py-1 leading-none text-xl font-semibold hover:bg-red-300 hover:text-red-600 rounded">
			X
		</button>
    </div>
</template>

<script>
export default {
    name: 'Alert',
    props: {
        menssagem: String,
    },
    data() {
        return {
            isVisible: Object.keys(this.menssagem ?? '').length > 0,
        };
    },

}
</script>

