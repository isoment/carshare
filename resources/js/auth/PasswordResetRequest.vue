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
                        <h2 class="font-bold text-lg mb-5 text-gray-600">Forgot Your Password?</h2>
                        <h4 class="font-thin mb-5 mt-2 text-gray-700">
                            Enter your email and we will send a reset link to change your password.
                        </h4>
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
                        <!-- Button -->
                        <div class="mt-4">
                            <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                                        duration-200 focus:outline-none py-2 px-4 w-full"
                                    :disabled="loading"
                                    @click.prevent="reset">
                                <span v-if="!loading">Reset Password</span>
                                <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Sending... </span>
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
                    email: null
                },
                loading: false,
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        },
        
        methods: {
            async reset() {
                this.loading = true;
                this.validationErrors = null;

                try {
                    const response = await axios.post('/password/email', this.user);
                    if (response.status === 200) {
                        this.$router.push({ name: "password-reset-email" });
                    }
                } catch (error) {
                    this.validationErrors = error.response.data.errors;
                }

                this.loading = false;
            }
        }

    }
</script>