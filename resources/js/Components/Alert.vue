<template>
    <div class="bg-green-100 text-green-700 rounded-lg py-3 px-4 mb-3 text-base inline-flex items-center w-full">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'Alert',
    props: {
        alertVisible: Boolean,
        default: false,
    },
}
</script>

