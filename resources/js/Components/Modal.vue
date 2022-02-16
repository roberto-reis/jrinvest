<template>
  <div v-if="isVisible">
    <div class="fixed inset-0 z-30 flex justify-center items-start mt-16">
        <div class="flex flex-col w-full md:w-1/4 max-w-2xl rounded-lg shadow-lg bg-white">
            <!-- Header -->
            <div class="p-2 border-b border-grey-500">
                <div class="flex justify-between items-start">
                    <h3 class="text-2xl font-semibold"> 
                        <slot name="header" />
                    </h3>
                    <button class="p-1 leading-none" @click="closeModal()">
                        <div class="text-xl font-semibold h-6 w-6 hover:bg-red-200 hover:text-red-600 rounded">
                            <span>X</span>
                        </div>
                    </button>
                </div>
            </div>
            <!-- Body -->
            <div class="p-6">
                <slot name="body" />
            </div>
            <!-- Footer -->
            <div class="py-3 px-2.5 flex justify-end items-center border-t border-grey-500">
                <button @click="closeModal()" class="py-1.5 px-3 rounded bg-red-600 text-white">Cancel</button>
                <slot name="footer" />
            </div>
        </div>
    </div>
    <div class="opacity-30 bg-black fixed inset-0 z-20">
    </div>
  </div>
</template>

<script>
export default {
    name: 'Modal',
    props: {
        isVisible: {
            type: Boolean,
            default: false
        }
    },
    methods: {
       closeModal() {
           this.$emit('close');
       }
    }
}
</script>

<style>

</style>