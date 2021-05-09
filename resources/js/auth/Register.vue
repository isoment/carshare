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
                        <h2 class="font-bold text-lg mb-5 text-gray-600">Sign Up</h2>
                        <!-- Name -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                                <input type="text" name="name" 
                                    placeholder="Name" required
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"/>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-envelope-square'></i>
                                <input name="email" placeholder="Email" required
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"/>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-lock'></i>
                                <input name="password" type='password' placeholder="Password"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"/>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-lock'></i>
                                <input name="password" type='password' placeholder="Re-type password"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"/>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="mt-4">
                            <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                                        duration-200 focus:outline-none py-2 px-4 w-full">
                                Sign up
                            </button>
                        </div>
                        <!-- Register -->
                        <div class="text-gray-500 mt-6 text-center">
                            Already have an account? 
                            <router-link :to="{ name: 'login' }" 
                                        class="text-purple-500 hover:text-purple-400 transition-all duration-200 font-bold">
                                Log in
                            </router-link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import validationErrors from './../shared/mixins/validationErrors';
    import { mapState } from 'vuex';

    export default {
        mixins: [validationErrors],

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        },
    }
</script>