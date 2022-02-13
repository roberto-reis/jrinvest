<template>
    <button :type="type" class="inline-block leading-normal px-3 py-2 bg-gray-800 rounded font-semibold text-sm text-white uppercase">
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
