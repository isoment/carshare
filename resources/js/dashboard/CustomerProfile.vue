<template>
    <div>
        <main-navigation></main-navigation>

        <div>
            <div v-if="!isLoggedIn">
                <unauthorized></unauthorized>
            </div>

            <div v-else>
                <!-- Banner -->
                <div class="customer-profile-banner h-36 md:h-56 border-b border-gray-200 pb-8">
                    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                        <div class="relative">
                            <div class="absolute right-2 lg:right-8 top-16 md:top-28">
                                <div v-if="!editProfileMode">
                                    <a href="#" 
                                       @click="editProfileMode = true"
                                       class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                              px-4 py-2 text-white font-bold border border-purple-500
                                              hover:border-purple-400">
                                        Edit profile
                                    </a>
                                </div>
                                <div v-else>
                                    <a href="#" 
                                       @click="editProfileMode = false"
                                       class="bg-white px-4 py-2 text-gray-800 border border-gray-800 
                                              font-bold mr-2">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6" 
                     v-if="user">
                     <div v-if="!editProfileMode">
                         <customer-profile-info :user="user"></customer-profile-info>
                     </div>
                    <div v-if="editProfileMode">
                        <customer-profile-edit :user="user"></customer-profile-edit>
                    </div>
                </div>
                <div class="text-center mt-8" v-else>
                    <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import CustomerProfileEdit from './CustomerProfileEdit.vue';
    import CustomerProfileInfo from './CustomerProfileInfo.vue';

    export default {
        components: { 
            CustomerProfileInfo,
            CustomerProfileEdit 
        },

        data() {
            return {
                isLoggedIn: null,
                user: null,
                editProfileMode: false,
            }
        },

        methods: {
            async getUser() {
                this.user = (await axios.get('/user')).data;
            }
        },

        created() {
            this.isLoggedIn = this.$store.state.isLoggedIn;

            this.getUser();
        }
    }
</script>