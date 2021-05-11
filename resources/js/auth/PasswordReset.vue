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
                        <h2 class="font-bold text-lg mb-5 text-gray-600">Reset Your Password</h2>
                        <!-- Email -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                                <input type="email" name="email" 
                                    placeholder="Email"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"
                                    v-model="user.email">
                            </div>
                            <validation-errors :errors="errorFor('email')"></validation-errors>
                        </div>
                        <!-- Password -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                                <input type="password" name="password" 
                                    placeholder="New Password"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"
                                    v-model="user.password">
                            </div>
                            <validation-errors :errors="errorFor('password')"></validation-errors>
                        </div>
                        <!-- Password Confirmation -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                                <input type="password" name="password" 
                                    placeholder="Confirm Password"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"
                                    v-model="user.password_confirmation">
                            </div>
                            <validation-errors :errors="errorFor('password_confirmation')"></validation-errors>
                        </div>
                        <!-- Verification Code -->
                        <div class="w-full mb-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                                <input type="text" name="verificationCode" 
                                    placeholder="Verification Code"
                                    class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"
                                    v-model="user.verificationCode">
                            </div>
                            <validation-errors :errors="errorFor('verificationCode')"></validation-errors>
                        </div>
                        <!-- Resend Verification -->
                        <div>
                            <router-link :to="{ name: 'password-reset-request' }"
                                        class="text-purple-500 hover:text-purple-400 transition-all duration-200 font-light">
                                Resend Verification Code
                            </router-link>
                        </div>
                        <!-- Button -->
                        <div class="mt-4">
                            <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                                        duration-200 focus:outline-none py-2 px-4 w-full"
                                    :disabled="loading"
                                    @click.prevent="reset">
                                Reset Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import validationErrors from './../shared/mixins/validationErrors';

    export default {
        mixins: [validationErrors],

        data() {
            return {
                user: {
                    email: null,
                    verificationCode: null,
                    password: null,
                    password_confirmation: null
                },
                loading: false,
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        },

        //  Vue hook to set the email param
        beforeRouteEnter(to, from, next) {
            next(vm => {
                vm.user.email = to.params.email
            });
        },
        
        methods: {
            async reset() {
                this.loading = true;
                this.validationErrors = null;

                try {

                } catch (error) {
                    this.validationErrors = error.response.data.errors;
                }

                this.loading = false;
            }
        }

    }
</script>