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
                                                @click="clearCart()"
                                                v-if="cart.items.length">
                                            Clear Cart
                                        </button>
                                    </div>

                                    <!-- Validation Errors -->
                                    <div class="mt-2 -mb-6" v-if="errors">
                                        <div v-for="error in errors" :key="error.id"
                                             class="text-red-400 text-xs my-1">
                                            {{error[0]}}
                                        </div>
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
                                                    <span class="text-sm font-medium text-purple-500">
                                                        Total: {{formatMoney(item.price.total)}}
                                                    </span>
                                                    <span class="ml-1 cursor-pointer"
                                                          @click="removeFromCart(item.id)">
                                                        <svg class="w-4 h-4 font-bold text-red-400 hover:text-red-500 transition-all duration-200" fill="none" stroke="currentColor" 
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
                                <div class="p-5 mt-6 md:mt-0 bg-gray-700 rounded overflow-visible card-input-element 
                                            flex flex-col justify-between"> 
                                    <div>
                                        <span class="text-xl font-medium text-gray-100 block pb-3">Continue to payment...</span> 
                                        <div class="overflow-visible flex justify-between items-center mt-2">
                                            <div class="rounded w-52 h-28 bg-purple-400 py-2 px-4 relative right-10"> 
                                                <span class="italic text-lg font-medium text-gray-200 underline">Card</span>
                                                <div class="flex justify-between items-center pt-4 "> 
                                                    <span class="text-xs text-gray-200 font-medium">****</span> 
                                                    <span class="text-xs text-gray-200 font-medium">****</span> 
                                                    <span class="text-xs text-gray-200 font-medium">****</span> 
                                                    <span class="text-xs text-gray-200 font-medium">1234</span> 
                                                </div>
                                                <div class="flex justify-between items-center mt-3"> 
                                                    <span class="text-xs text-gray-200">Jane Smith</span> 
                                                    <span class="text-xs text-gray-200">12/55</span> 
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-center w-20 lg:w-36">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 468 222.5" style="enable-background:new 0 0 468 222.5;" xml:space="preserve"
                                                    class="fill-current text-purple-400">
                                                    <g>
                                                        <path class="st0" d="M414,113.4c0-25.6-12.4-45.8-36.1-45.8c-23.8,0-38.2,20.2-38.2,45.6c0,30.1,17,45.3,41.4,45.3   c11.9,0,20.9-2.7,27.7-6.5v-20c-6.8,3.4-14.6,5.5-24.5,5.5c-9.7,0-18.3-3.4-19.4-15.2h48.9C413.8,121,414,115.8,414,113.4z    M364.6,103.9c0-11.3,6.9-16,13.2-16c6.1,0,12.6,4.7,12.6,16H364.6z"/>
                                                        <path class="st0" d="M301.1,67.6c-9.8,0-16.1,4.6-19.6,7.8l-1.3-6.2h-22v116.6l25-5.3l0.1-28.3c3.6,2.6,8.9,6.3,17.7,6.3   c17.9,0,34.2-14.4,34.2-46.1C335.1,83.4,318.6,67.6,301.1,67.6z M295.1,136.5c-5.9,0-9.4-2.1-11.8-4.7l-0.1-37.1   c2.6-2.9,6.2-4.9,11.9-4.9c9.1,0,15.4,10.2,15.4,23.3C310.5,126.5,304.3,136.5,295.1,136.5z"/>
                                                        <polygon class="st0" points="223.8,61.7 248.9,56.3 248.9,36 223.8,41.3  "/>
                                                        <rect x="223.8" y="69.3" class="st0" width="25.1" height="87.5"/>
                                                        <path class="st0" d="M196.9,76.7l-1.6-7.4h-21.6v87.5h25V97.5c5.9-7.7,15.9-6.3,19-5.2v-23C214.5,68.1,202.8,65.9,196.9,76.7z"/>
                                                        <path class="st0" d="M146.9,47.6l-24.4,5.2l-0.1,80.1c0,14.8,11.1,25.7,25.9,25.7c8.2,0,14.2-1.5,17.5-3.3V135   c-3.2,1.3-19,5.9-19-8.9V90.6h19V69.3h-19L146.9,47.6z"/>
                                                        <path class="st0" d="M79.3,94.7c0-3.9,3.2-5.4,8.5-5.4c7.6,0,17.2,2.3,24.8,6.4V72.2c-8.3-3.3-16.5-4.6-24.8-4.6   C67.5,67.6,54,78.2,54,95.9c0,27.6,38,23.2,38,35.1c0,4.6-4,6.1-9.6,6.1c-8.3,0-18.9-3.4-27.3-8v23.8c9.3,4,18.7,5.7,27.3,5.7   c20.8,0,35.1-10.3,35.1-28.2C117.4,100.6,79.3,105.9,79.3,94.7z"/>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="my-4 text-white text-sm lg:text-base">
                                            <h6>Make any changes to your bookings here then continue to the payment page. All major credit cards
                                                are accepted through our payment processor Stripe.
                                            </h6>
                                        </div>
                                    </div>
                                    <router-link :to="{ name: 'payment', params: { total: cartTotal() } }"
                                            class="w-full focus:outline-none text-white text-center py-2 font-semibold"
                                            :disabled="!cart.items.length"
                                            :event="cart.items.length ? 'click' : ''"
                                            :class="{
                                                'bg-gray-400': !cart.items.length,
                                                'bg-purple-500 hover:bg-purple-400 transition-all duration-200': cart.items.length
                                            }">
                                        Payment
                                    </router-link>
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

            errors() {
                return this.$route.params.errors;
            }
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
        max-height: 27rem;
    }
</style>