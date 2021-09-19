<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn || !pid">
            <unauthorized></unauthorized>
        </div>

        <div v-else>
            <div class="md:mt-8 flex items-center justify-center px-5 pb-10 pt-16">
                <div class="w-full mx-auto rounded-lg bg-white shadow-lg border p-5 text-gray-700" style="max-width: 600px">
                    <div class="w-full pt-1 pb-5">
                        <div class="bg-green-500 text-white overflow-hidden rounded-full w-20 h-20 -mt-16 
                                    mx-auto shadow-lg flex justify-center items-center">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-boldnosans font-bold">Thank you for booking with us!</h3>
                        <h6 class="mt-4">We have sent you an email with the details of your booking. Our hosts look forward to providing a great experience for you</h6>
                    </div>
                    <div class="flex items-center justify-center my-3">
                        <svg class="w-24 h-24 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="text-center mb-4">
                        <h3 class="text-2xl font-boldnosans font-bold text-center">Confirmation:</h3>
                        <h3 class="text-green-400 text-2xl font-boldnosans font-bold">{{ pid }}</h3>
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
                user: "user",
                isLoggedIn: "isLoggedIn",
            }),

            pid() {
                return this.$route.params.pid;
            }
        }
    }
</script>