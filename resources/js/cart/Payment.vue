<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn || !total">
            <unauthorized></unauthorized>
        </div>

        <div v-else>
            <div class="md:mt-8 flex items-center justify-center px-5 pb-10 pt-16">
                <div class="w-full mx-auto rounded-lg bg-white shadow-lg border p-5 text-gray-700" style="max-width: 600px">
                    <div class="w-full pt-1 pb-5">
                        <div class="bg-purple-500 text-white overflow-hidden rounded-full w-20 h-20 -mt-16 
                                    mx-auto shadow-lg flex justify-center items-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <router-link class="flex cursor-pointer" :to="{name: 'shopping-cart'}">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </router-link>
                    </div>
                    <div class="mb-10">
                        <h1 class="text-center font-bold text-xl font-boldnosans">Total Charge</h1>
                        <h3 class="text-center font-bold text-3xl text-purple-500 font-boldnosans">{{ total }}</h3>
                    </div>
                    <div class="mb-1 text-red-400 text-xs font-semibold">
                        {{ stripeValidationErrors }}
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">Card number</label>
                        <div>
                            <div id="card-number-element"></div>
                        </div>
                    </div>
                    <div class="mb-3 -mx-2 flex items-end">
                        <div class="px-2 w-1/2">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">Expiration Date</label>
                            <div>
                                <div id="card-expiry-element"></div>
                            </div>
                        </div>
                        <div class="px-2 w-1/2">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">CCV</label>
                            <div>
                                <div id="card-cvc-element"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button class="block w-full max-w-xs mx-auto bg-purple-500 hover:bg-purple-400 focus:bg-purple-700 
                                      text-white px-3 py-3 transition-all duration-200">
                            <div class="flex justify-center items-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg> 
                                <span class="ml-2">Pay now</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                stripe: null,
                cardNumberElement: null,
                cardExpiryElement: null,
                cardCVCElement: null,
                stripeValidationErrors: null
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                cart: "cart"
            }),

            total() {
                return this.$route.params.total;
            }
        },

        methods: {
            // Insert the Stripe inputs inot the DOM
            inputElements() {
                let elements = this.stripe.elements();

                this.cardNumberElement = elements.create("cardNumber", {
                    classes: {
                        base: this.inputTailwindClasses()
                    }
                });
                this.cardNumberElement.mount("#card-number-element");

                this.cardExpiryElement = elements.create("cardExpiry", {
                    classes: {
                        base: this.inputTailwindClasses()
                    }
                });
                this.cardExpiryElement.mount("#card-expiry-element");

                this.cardCvcElement = elements.create("cardCvc", {
                    classes: {
                        base: this.inputTailwindClasses()
                    }
                });
                this.cardCvcElement.mount("#card-cvc-element");

                this.cardNumberElement.on("change", this.setValidationError);
                this.cardExpiryElement.on("change", this.setValidationError);
                this.cardCvcElement.on("change", this.setValidationError);
            },

            setValidationError(event) {
                this.stripeValidationErrors = event.error ? event.error.message : "";
            },

            inputTailwindClasses() {
                return "px-2 py-2 border border-gray-300 text-sm w-full";
            },
        },

        async mounted() {
            this.stripe = Stripe(process.env.MIX_STRIPE_KEY);

            this.inputElements();
        }
    }
</script>

<style>
    .input-width {
        max-width: 100px;
    }

    .payment-bar {
        height: 1.65rem;
        width: 8.5rem;
        z-index: 0;
        bottom: 0.7rem;
        left: 1.5rem;
    }

    @media screen and (min-width: 768px) {
        .payment-bar {
            height: 1.95rem;
            width: 12.5rem;
            z-index: 0;
            bottom: 2.5rem;
            left: 1rem;
        }
    }
</style>