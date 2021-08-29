<template>
    <div>
        <div v-if="!isLoggedIn">
            <unauthorized></unauthorized>
        </div>
        <div v-else>
            <main-navigation></main-navigation>

            <div class="md:py-12">
                <div class="max-w-md mx-auto bg-gray-100 shadow-lg md:rounded-lg md:max-w-5xl">
                    <div class="md:flex ">
                        <div class="w-full p-4 px-5 py-5">
                            <div class="md:grid md:grid-cols-3 gap-2 ">
                                <div class="col-span-2 p-5">
                                    <h1 class="text-xl font-medium ">Shopping Cart</h1>

                                    <!-- Cart Item -->
                                    <div class="flex justify-between items-center mt-6 pt-6">
                                        <div class="flex items-center"> 
                                            <img src="/img/vehicle-makes/ford.jpg" class="rounded-full w-14 h-14">
                                            <div class="flex flex-col ml-3"> 
                                                <span class="md:text-md font-medium">Vehicle Name</span> 
                                                <span class="text-xs font-light text-gray-400">Dates</span> 
                                            </div>
                                        </div>
                                        <div class="flex justify-center items-center">
                                            <div class="pr-8 "> 
                                                <span class="text-xs font-medium">$10.50</span> 
                                            </div>
                                            <div> 
                                                <i class="fa fa-close text-xs font-medium"></i> 
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cart Item -->
                                    <div class="flex justify-between items-center mt-6 pt-6">
                                        <div class="flex items-center"> 
                                            <img src="/img/vehicle-makes/ford.jpg" class="rounded-full w-14 h-14">
                                            <div class="flex flex-col ml-3"> 
                                                <span class="md:text-md font-medium">Vehicle Name</span> 
                                                <span class="text-xs font-light text-gray-400">Dates</span> 
                                            </div>
                                        </div>
                                        <div class="flex justify-center items-center">
                                            <div class="pr-8 "> 
                                                <span class="text-xs font-medium">$10.50</span> 
                                            </div>
                                            <div> 
                                                <i class="fa fa-close text-xs font-medium"></i> 
                                            </div>
                                        </div>
                                    </div>


                                    <div class="flex justify-between items-center mt-6 pt-6 border-t">
                                        <router-link class="flex items-center"
                                                     :to="{name: 'main-vehicle'}"> 
                                            <i class="fa fa-arrow-left text-sm pr-2"></i> 
                                            <span class="text-md font-medium text-purple-500">Back to Vehicles</span>
                                        </router-link>
                                        <div class="flex justify-center items-end"> 
                                            <span class="text-sm font-medium text-gray-400 mr-1">Subtotal:</span> 
                                            <span class="text-lg font-bold text-gray-800 "> $24.90</span> 
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 bg-gray-800 rounded overflow-visible card-input-element"> 
                                    <span class="text-xl font-medium text-gray-100 block pb-3">Card Details</span> 
                                    <span class="text-xs text-gray-400 ">Card Type</span>
                                    <div class="overflow-visible flex justify-between items-center mt-2">
                                        <div class="rounded w-52 h-28 bg-purple-400 py-2 px-4 relative right-10"> 
                                            <span class="italic text-lg font-medium text-gray-200 underline">VISA</span>
                                            <div class="flex justify-between items-center pt-4 "> 
                                                <span class="text-xs text-gray-200 font-medium">****</span> 
                                                <span class="text-xs text-gray-200 font-medium">****</span> 
                                                <span class="text-xs text-gray-200 font-medium">****</span> 
                                                <span class="text-xs text-gray-200 font-medium">****</span> 
                                            </div>
                                            <div class="flex justify-between items-center mt-3"> 
                                                <span class="text-xs text-gray-200">Customer Name</span> 
                                                <span class="text-xs text-gray-200">12/25</span> 
                                            </div>
                                        </div>
                                        <div class="flex justify-center items-center flex-col"> 
                                            <img src="https://img.icons8.com/color/96/000000/mastercard-logo.png" width="40" class="relative right-5" /> 
                                            <span class="text-xs font-medium text-gray-200 bottom-2 relative right-5">mastercard.</span> 
                                        </div>
                                    </div>
                                    <div class="flex justify-center flex-col pt-3"> 
                                        <label class="text-xs text-gray-400 ">Name on Card</label> 
                                        <div class="focus:outline-none w-full h-6 bg-gray-800 text-white placeholder-gray-300 text-sm 
                                                    border-b border-gray-600 py-4"
                                             id="card-number-name"></div>
                                    </div>
                                    <div class="flex justify-center flex-col pt-3"> 
                                        <label class="text-xs text-gray-400 ">Card Number</label> 
                                        <div class="focus:outline-none w-full h-6 bg-gray-800 text-white placeholder-gray-300 text-sm 
                                                    border-b border-gray-600 py-4"> 
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 pt-2 mb-3">
                                        <div class="col-span-2 "> 
                                            <label class="text-xs text-gray-400">Expiration Date</label>
                                            <div class="grid grid-cols-2 gap-2"> 
                                                <div class="focus:outline-none w-full h-6 bg-gray-800 text-white placeholder-gray-300 text-sm 
                                                            border-b border-gray-600 py-4"> 
                                                </div>
                                                <div class="focus:outline-none w-full h-6 bg-gray-800 text-white placeholder-gray-300 text-sm 
                                                            border-b border-gray-600 py-4"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class=""> 
                                            <label class="text-xs text-gray-400">CVV</label> 
                                            <div class="focus:outline-none w-full h-6 bg-gray-800 text-white placeholder-gray-300 text-sm border-b border-gray-600 py-4">
                                            </div>
                                        </div>
                                    </div> 
                                    <button class="h-12 w-full bg-purple-500 rounded focus:outline-none text-white hover:bg-purple-400">Check Out</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        },
    }
</script>

<style>
    .card-input-element {
        max-height: 30rem;
    }
</style>