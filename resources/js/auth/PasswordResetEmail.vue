<template>
    <div>
        <div v-if="isLoggedIn">
            <unauthorized></unauthorized>
        </div>
        <div v-else>
            <main-navigation></main-navigation>
            <div class="w-full h-screen flex items-center justify-center navbar-offset">
                <form class="w-full md:w-1/2 max-w-xl bg-white lg:border border-cool-gray-300 rounded-lg">
                    <div class="flex font-bold justify-center mb-3 lg:mb-4 lg:mt-12">
                        <router-link :to="{ name: 'main-page' }">
                            <i class="fas fa-car text-7xl text-purple-400"></i>
                        </router-link>
                    </div>
                    <div class="px-12 pb-10">
                        <h2 class="font-bold text-lg mb-5 text-gray-600">Please check your email and click on the reset link.</h2>
                        <h4 class="font-thin mb-5 mt-2 text-gray-700">
                            Didn't receive an email? Send a new reset link...
                        </h4>
                        <!-- Resend Verification -->
                        <div>
                            <router-link :to="{ name: 'password-reset-request' }"
                                        class="text-purple-500 hover:text-purple-400 transition-all duration-200 font-light">
                                Resend Verification Code
                            </router-link>
                        </div>
                    </div>
                </form>
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