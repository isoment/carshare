<template>
    <div>
        <main-navigation></main-navigation>
        <div class="text-purple-400 text-center font-bold1 font-bold text-4xl tracking-widest mt-4">
            Count: {{ this.$store.state.count }}
        </div>
        <div class="my-3 text-center">
            <button class="bg-purple-500 text-white font-bold text-sm p-2 hover:bg-purple-400 transition-all duration-200"
                    @click="increment">
                Click Me
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            increment() {
                this.$store.commit('increment');
            }
        }
    }
</script>
