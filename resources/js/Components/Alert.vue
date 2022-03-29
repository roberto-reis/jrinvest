<template>
    <div v-if="isVisible" class="z-50 shadow-md rounded-lg py-3 px-4 mb-3 text-base inline-flex justify-between items-center w-full">
        {{ menssagem }}
        <button type="button" @click="isVisible = false" class="-mr-2 ml-3 px-1.5 py-1 leading-none text-xl font-bold text-red-500 hover:bg-red-300 hover:text-red-600 rounded">
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

