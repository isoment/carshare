<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <!-- Hero Section -->
        <section class="pb-60 pt-24 hero-background">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold text-white font-boldnosans">Don't rent... Share.</h1>
                    <h3 class="text-xl text-white font-bold tracking-wider mt-2">Book awesome, unique cars in your area.</h3>
                </div>
                <div class="flex justify-center mt-3">
                    <div class="bg-white rounded-full pl-5 pr-16 pt-2 pb-1 flex">
                        <!-- Zip search -->
                        <div class="flex flex-col pr-2 border-r border-gray-300">
                            <label for="zip" class="text-xs font-bold text-gray-600">Zipcode</label>
                            <input type="text" name="zip" class="focus:outline-none">
                        </div>
                        <div class="relative">
                            <div class="flex justify-around">
                                <!-- Start date -->
                                <div class="flex flex-col border-r border-gray-300 px-3">
                                    <label for="from" class="text-xs font-bold text-gray-600">From</label>
                                    <input type="date" name="from" class="focus:outline-none">
                                </div>
                                <!-- Until date -->
                                <div class="flex flex-col px-3">
                                    <label for="until" class="text-xs font-bold text-gray-600">Until</label>
                                    <input type="date" name="until" class="focus:outline-none">
                                </div>
                            </div>
                            <div class="absolute magnifier-button">
                                <button class="text-white bg-purple-500 hover:bg-purple-400 rounded-full 
                                               focus:outline-none">
                                    <i class="fas fa-search text-2xl w-12 h-12 pt-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vuex Test -->
        <div>
            <div class="text-purple-400 text-center font-boldnosans font-bold text-4xl tracking-widest mt-4">
                Count: {{ count }}
            </div>
            <div class="my-3 text-center">
                <button class="bg-purple-500 text-white font-bold text-sm p-2 hover:bg-purple-400 transition-all duration-200"
                        @click="increment">
                    Click Me
                </button>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        computed: {
            count() {
                return this.$store.state.count;
            }
        },

        methods: {
            increment() {
                this.$store.commit('increment');
            }
        }
    }
</script>
