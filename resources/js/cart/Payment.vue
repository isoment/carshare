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
                    </div>
                    <div class="mb-10">
                        <h1 class="text-center font-bold text-xl font-boldnosans">Total Charge</h1>
                        <h3 class="text-center font-bold text-3xl text-purple-500 font-boldnosans">{{ total }}</h3>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">Name on card</label>
                        <div>
                            <input class="px-2 py-2 border border-gray-300 text-sm w-full" placeholder="John Smith" type="text"/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">Card number</label>
                        <div>
                            <input class="px-2 py-2 border border-gray-300 text-sm w-full" placeholder="0000 0000 0000 0000" type="text"/>
                        </div>
                    </div>
                    <div class="mb-3 -mx-2 flex items-end">
                        <div class="px-2 w-1/2">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">Expiration Date</label>
                            <div>
                                <input class="px-2 py-2 border border-gray-300 text-sm w-full" 
                                       placeholder="xx/xx" type="text"/>
                            </div>
                        </div>
                        <div class="px-2 w-1/2">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-wider">CCV</label>
                            <div>
                                <input class="px-2 py-2 border border-gray-300 text-sm w-full" 
                                       placeholder="XXX" type="text"/>
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
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                cart: "cart"
            }),

            total() {
                return this.$route.params.total;
            }
        },
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