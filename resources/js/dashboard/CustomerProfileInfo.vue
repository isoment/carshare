<template>
    <div class="grid grid-cols-1 md:grid-cols-2" v-if="user.profile">
        <div class="relative md:mr-20">
            <div class="absolute -top-14 mb-12">
                <img :src="avatar(user.profile.image)"
                    alt="avatar"
                    class="rounded-full h-28 w-28 border-8 border-white">
            </div>
            <div class="mt-20">
                <h3 class="text-3xl font-extrabold">{{user.name}}</h3>
                <h5 class="text-sm mt-1">Joined {{dateMonthYear(user.created_at)}}</h5>
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
                        <router-link class="text-purple-500 font-bold text-sm"
                                     :to="{ name: 'drivers-license' }">
                            Verify ID
                        </router-link>
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
        <div class="mt-12 md:flex-col md:justify-center md:items-center md:mt-0">
            <div class="pt-12">
                <h6 class="uppercase font-bold font-boldnosans text-sm 
                        text-gray-600 tracking-widest mb-2">
                    Reviews from hosts
                </h6>
                <users-reviews-from-hosts></users-reviews-from-hosts>
            </div>
            <div class="pt-12">
                <h6 class="uppercase font-bold font-boldnosans text-sm 
                        text-gray-600 tracking-widest mb-2">
                    Reviews from renters
                </h6>
                <users-reviews-from-renters></users-reviews-from-renters>
            </div>
        </div>
    </div>
</template>

<script>
    import UsersReviewsFromHosts from './../review/UsersReviewsFromHosts.vue';
    import UsersReviewsFromRenters from './../review/UsersReviewsFromRenters.vue';
    import { dateFormatMonthYear } from './../shared/utils/dateFormats';
    import avatarHelper from './../shared/mixins/avatarHelper';

    export default {
        props: {
            user: Object
        },

        mixins: [avatarHelper],

        components: {
            UsersReviewsFromHosts,
            UsersReviewsFromRenters
        },

        methods: {
            dateMonthYear(date) {
                return dateFormatMonthYear(date);
            }
        }
    }
</script>