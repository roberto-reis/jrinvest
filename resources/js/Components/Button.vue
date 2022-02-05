<template>
    <button :type="type" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'submit',
        },
    }
}
</script>
