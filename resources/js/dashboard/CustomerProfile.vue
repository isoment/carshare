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
                                <a href="#" 
                                class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                        px-4 py-2 text-white font-bold">
                                    Edit profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  -->
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6" 
                     v-if="user">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="relative md:mr-20">
                            <div class="absolute -top-14 mb-12">
                                <img :src="user.profile.image" 
                                    alt="avatar"
                                    class="rounded-full h-28 w-28 border-8 border-white">
                            </div>
                            <div class="mt-20">
                                <h3 class="text-3xl font-extrabold">{{user.name}}</h3>
                                <h5 class="text-sm mt-1">Joined {{dateFormat(user.created_at)}}</h5>
                            </div>
                            <div class="mt-10">
                                <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest mb-4">
                                    Verified Info
                                </h6>
                                <div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm">
                                            Approved to drive
                                        </div>
                                        <a href="#" class="text-purple-500 font-bold text-sm">
                                            Verify license
                                        </a>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="text-sm">
                                            Email address
                                        </div>
                                        <a href="#" class="text-purple-500 font-bold text-sm">
                                            Verify email
                                        </a>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="text-sm">
                                            Profile
                                        </div>
                                        <a href="#" class="text-purple-500 font-bold text-sm">
                                            Complete your profile
                                        </a>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="text-sm">
                                            Biography
                                        </div>
                                        <a href="#" class="text-purple-500 font-bold text-sm">
                                            Add a bio
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-3 leading-relaxed">
                                        Build trust with other users on Carshare now and complete the above steps &#128077;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-12 md:flex md:justify-center md:items-center md:mt-0">
                            <div>
                                <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-600 tracking-widest mb-2">
                                    Reviews from hosts
                                </h6>
                                <no-reviews :message="user.name + ' has no reviews yet'"></no-reviews>
                            </div>
                        </div>
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
    import NoReviews from "./../review/NoReviews.vue";
    import moment from "moment";
    import { mapState } from 'vuex';

    export default {
        components: {
            NoReviews
        },

        data() {
            return {
                isLoggedIn: null,
                user: null
            }
        },

        methods: {
            dateFormat(date) {
                return moment(date).format('MMMM YYYY');
            },

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