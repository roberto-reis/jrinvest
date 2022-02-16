<template>
    <transition name="slide-fade">
        <div v-if="isVisibleToast" class="absolute top-16 right-0 w-96 mr-4 bg-red-200 text-red-800 z-50 shadow-md rounded-lg py-3 px-4 mb-3 text-base inline-flex justify-between items-center">
            {{ errors }}
            <button type="button" @click="isVisibleToast = false" class="-mr-2 px-1.5 py-1 leading-none text-xl font-semibold hover:bg-red-300 hover:text-red-600 rounded">
                X
            </button>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'Toast',
    data() {
        return {
            isVisibleToast: false,
            timeout: null,
        };
    },
    computed: {
        errors() {
            return this.$page.props.flash.error
        },
    },
    watch: {
        errors: {
            deep: true,
            handler() {
                this.isVisibleToast = Object.keys(this.errors ?? '').length > 0;

                if (this.timeout) {
                    clearTimeout(this.timeout);
                }

                this.timeout = setTimeout(() => {
                    this.isVisibleToast = false;
                }, 3000);
            }
        },
    }
}
</script>

<style>
    /* Animações de entrada e saída podem utilizar diferentes  */
    /* funções de duração e de tempo.                          */
    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active em versões anteriores a 2.1.8 */ {
        transform: translateX(150px);
        opacity: 0;
    }
</style>
