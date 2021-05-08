<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <!-- Hero Section -->
        <section class="pb-24 md:pb-60 pt-24 hero-background">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold text-white font-boldnosans">Don't rent... Share.</h1>
                    <h3 class="text-xl text-white font-bold tracking-wider mt-2">Book awesome, unique cars in your area.</h3>
                </div>
                <div class="flex justify-center mt-3">
                    <div class="bg-white rounded-lg md:rounded-full py-2 px-2 md:pl-5 md:pr-16 md:pt-2 md:pb-1 
                                flex flex-col md:flex-row w-11/12 sm:w-3/4 md:w-auto shadow-lg">
                        <!-- Zip search -->
                        <div class="flex flex-col md:pr-2 border-b md:border-b-0 md:border-r border-gray-300 my-2 md:my-0">
                            <label for="zip" class="text-xs font-bold text-gray-500">Zipcode</label>
                            <input type="text" name="zip" class="focus:outline-none font-semibold text-sm">
                        </div>
                        <div class="relative">
                            <div class="flex flex-col md:flex-row justify-around">
                                <!-- Start date -->
                                <div class="flex flex-col md:border-r border-gray-300 md:px-3 border-b 
                                            md:border-b-0 my-2 md:my-0">
                                    <label for="from" class="text-xs font-bold text-gray-500">From</label>
                                    <input type="date" name="from" class="focus:outline-none">
                                </div>
                                <!-- Until date -->
                                <div class="flex flex-col md:px-3 border-b md:border-b-0 border-gray-300 my-2 md:my-0">
                                    <label for="until" class="text-xs font-bold text-gray-500">Until</label>
                                    <input type="date" name="until" class="focus:outline-none">
                                </div>
                            </div>
                            <div class="absolute magnifier-button hidden md:block">
                                <button class="text-white bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                               rounded-full focus:outline-none">
                                    <i class="fas fa-search text-2xl w-12 h-12 pt-2"></i>
                                </button>
                            </div>
                            <div class="md:hidden">
                                <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                                               duration-200 focus:outline-none py-2 px-4 w-full">
                                    <div class="flex items-center justify-center">
                                        <div class="mr-2">Search for cars</div>
                                        <i class="fas fa-search"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        computed: {},

        methods: {}
    }
</script>
