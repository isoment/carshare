<template>
    <div>
        <div v-if="!isLoggedIn">
            <unauthorized></unauthorized>
        </div>
        <div v-else>
            <main-navigation></main-navigation>

            <div class="md:py-12">
                <div class="max-w-md mx-auto bg:white md:bg-gray-100 shadow-lg md:rounded-lg md:max-w-6xl">
                    <div class="md:flex ">
                        <div class="w-full p-4 px-5 py-5">
                            <div class="md:grid md:grid-cols-3 gap-2 ">
                                <div class="col-span-2 md:p-5">
                                    <div class="flex justify-between items-center">
                                        <h1 class="text-2xl font-boldnosans font-bold">Shopping Cart</h1>
                                        <button class="text-xs font-bold border border-red-400 text-red-400 hover:border-red-500 
                                                      hover:text-red-500 p-1 cursor-pointer focus:outline-none transition-all duration-200"
                                                @click="clearCart()">
                                            Clear Cart
                                        </button>
                                    </div>

                                    <!-- Cart Items -->
                                    <div v-for="item in cart.items" :key="item.id">
                                        <div class="flex flex-col md:flex-row md:justify-between md:items-center 
                                                    mt-6 pt-6">
                                            <div class="flex items-center"> 
                                                <img :src="item.vehicle.vehicle_images[0]" 
                                                     class="h-14 w-20 rounded-sm">
                                                <div class="flex flex-col ml-3"> 
                                                    <span class="flex flex-col md:text-md font-medium">
                                                        <span class="text-purple-500 text-sm">
                                                            {{ item.vehicle.host_name + '\'s' }}
                                                        </span>
                                                        <span>
                                                            {{
                                                                item.vehicle.year + 
                                                                ' ' + item.vehicle.vehicle_make + 
                                                                ' ' + item.vehicle.vehicle_model
                                                            }}
                                                        </span>
                                                    </span> 
                                                    <span class="text-xs font-light text-gray-500">
                                                        {{ 
                                                            formatDate(item.dates.start) + 
                                                            ' - ' + formatDate(item.dates.end) 
                                                        }}
                                                    </span> 
                                                    <span class="text-xs font-light text-purple-500">
                                                        {{ 
                                                            formatMoney(item.price.price_day) + ' / ' + 
                                                            item.price.days + ' days' 
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-1 md:mt-0 md:pr-6">
                                                <div class="flex items-center"> 
                                                    <span class="md:text-sm font-light md:font-medium text-purple-500">
                                                        Total: {{formatMoney(item.price.total)}}
                                                    </span>
                                                    <span class="ml-1 cursor-pointer"
                                                          @click="removeFromCart(item.id)">
                                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" 
                                                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </span> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mt-6 pt-6 border-t">
                                        <router-link class="flex items-center"
                                                     :to="{name: 'main-vehicle'}"> 
                                            <i class="fa fa-arrow-left text-sm pr-2"></i> 
                                            <span class="text-md font-medium text-purple-500">Back to Vehicles</span>
                                        </router-link>
                                        <div class="flex justify-center items-end pr-6"> 
                                            <span class="font-medium text-gray-400 mr-1">Total:</span> 
                                            <span class="font-bold text-gray-800 "> {{cartTotal()}}</span> 
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 mt-6 md:mt-0 bg-gray-800 rounded overflow-visible card-input-element"> 
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
                                    <button class="h-12 w-full bg-purple-500 focus:outline-none text-white hover:bg-purple-400">Check Out</button>
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
    import { dollarFormat } from './../shared/utils/currency';
    import { monthDayYearNumbericSlash } from './../shared/utils/dateFormats';

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                cart: "cart"
            }),
        },

        methods: {
            formatMoney(value) {
                return dollarFormat(value);
            },

            formatDate(value) {
                return monthDayYearNumbericSlash(value);
            },

            cartTotal() {
                let arr = [];

                this.cart.items.forEach(element => arr.push(element.price.total));

                return dollarFormat(arr.reduce((a, b) => a + b, 0));
            },

            clearCart() {
                if (confirm('Do you really want to clear the cart?')) {
                    this.$store.dispatch('clearCart');

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Cart cleared!'
                    })
                }
            },

            removeFromCart(itemId) {
                this.$store.dispatch('removeFromBasket', itemId);

                this.$store.dispatch('addNotification', {
                    type: 'success',
                    message: 'Removed from cart!'
                });
            }
        }
    }
</script>

<style>
    .card-input-element {
        max-height: 30rem;
    }
</style>