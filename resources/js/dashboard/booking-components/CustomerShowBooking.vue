<template>
    <div>
        <main-navigation></main-navigation>
        <div v-if="!isLoggedIn">
            <error :message="'Not Authorized'"></error>
        </div>
        <div v-else-if="loading" class="text-center mt-8">
            <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
        </div>
        <div v-else-if="forbidden">
            <error :message="'You are not associated with this booking'"></error>
        </div>
        <div v-else>
            <div class="customer-profile-banner h-36 md:h-56 border-b border-gray-200 pb-8">
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative">
                        <div class="absolute right-2 lg:right-8 top-16 md:top-28">
                            <div>
                                <a href="#"
                                    class="bg-white px-4 py-2 text-gray-800 border-2 border-gray-800 
                                            font-bold mr-2">
                                    Cancel Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Left -->
                    <div class="relative md:mr-20">
                        <div class="absolute -top-14 mb-12">
                            <div class="h-28 w-28 rounded-full border-white border-8 absolute"
                                    :style="{ 'background-image': 'url(' + booking.vehicle.image + ')' }"
                                    style="background-size: cover; background-position: 50% 50%;">
                            </div>
                        </div>
                        <div class="mt-20">
                            <h5 class="text-sm font-light text-purple-600">
                                {{userIsHost ? 'Your...' : 'You booked the...'}}
                            </h5>
                            <h2 class="text-4xl font-boldnosans font-bold">
                                {{booking.vehicle.year}} {{booking.vehicle.make}}
                            </h2>
                            <h3 class="text-2xl font-boldnosans font-bold tracking-wider text-gray-700">
                                {{booking.vehicle.model}}
                            </h3>
                            <div class="mt-2 text-sm text-gray-600 font-bold">
                                Booked on: <span class="font-medium text-gray-400 tracking-widest">
                                    {{formatDate(booking.booking.created_at)}}</span>
                            </div>
                            <div class="mt-2" v-if="userIsRenter">
                                <button class="bg-purple-400 text-white text-center px-4 py-1 text-sm font-semibold
                                               hover:bg-purple-300 transition-all duration-200 focus:outline-none">
                                    <span><i class="far fa-list-alt"></i></span>
                                    Show Order
                                </button>
                            </div>
                            <div class="mt-10">
                                <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-700 tracking-widest mb-4 underline">
                                    Booking Details
                                </h6>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-sm">
                                        Booking ID:
                                    </div>
                                    <div href="#" class="text-gray-500 font-bold text-sm">
                                        {{booking.booking.id}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-sm">
                                        Date From:
                                    </div>
                                    <div href="#" class="text-purple-500 font-bold text-sm">
                                        {{formatDate(booking.booking.from)}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-sm">
                                        Date To:
                                    </div>
                                    <div href="#" class="text-purple-500 font-bold text-sm">
                                        {{formatDate(booking.booking.to)}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10">
                                <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-700 tracking-widest mb-4 underline">
                                    Booking Price
                                </h6>
                                <div class="flex justify-between items-center">
                                    <div class="uppercase font-bold font-boldnosans text-xs text-gray-500 tracking-widest">
                                        Price / Day:
                                    </div>
                                    <div href="#" class="text-xl font-bold">
                                        {{currencyFormat(booking.booking.price_day)}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="uppercase font-bold font-boldnosans text-xs text-gray-500 tracking-widest">
                                        Total / Days:
                                    </div>
                                    <div href="#" class="text-xl font-bold pl-20 border-b border-gray-200 pb-2">
                                        <span class="text-sm text-gray-400 font-light">x</span> {{bookingDateCount()}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="uppercase font-bold font-boldnosans text-xs text-gray-500 tracking-widest">
                                        Price Total:
                                    </div>
                                    <div href="#" class="text-purple-500 text-xl font-bold">
                                        {{currencyFormat(booking.booking.price_total)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right -->
                    <div>
                        <div class="mt-8">
                            <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-600 tracking-widest mb-4">
                                {{userIsHost ? 'Your Renter' : 'Your Host'}}
                            </h6>
                            <div class="flex items-center">
                                <div class="relative">
                                    <img :src="booking.user.image" alt="profile" 
                                        class="rounded-full h-20 w-20">
                                    <div class="absolute bg-white rounded-full px-4 shadow-lg text-center top-16
                                                font-bold font-boldnosans flex items-center border border-gray-100">
                                        <div class="mr-2">{{userRating}}</div>
                                        <div><i class="fas fa-star text-purple-400 text-medium"></i></div>
                                    </div>
                                </div>
                                <div class="text-gray-700 ml-3">
                                    <div class="font-bold text-lg">{{booking.user.name}}</div>
                                    <div class="text-xs">Member since {{humanReadableDate(booking.user.created_at)}}</div>
                                </div>
                            </div>
                            <div class="mt-8 flex justify-center items-center">
                                <div class="shadow-lg px-4 py-1 rounded-full border border-gray-100 w-32 text-center
                                            cursor-pointer font-boldnosans text-gray-800"
                                     v-if="!showBio"
                                     @click="toggleBio()">
                                    Show Bio <span><i class="fas fa-chevron-down"></i></span>
                                </div>
                            </div>
                            <div class="mt-4" v-if="showBio">
                                <div>
                                    <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest">Location</h6>
                                    <div class="text-xs mt-1">{{booking.user.location ? booking.user.location : 'N/A'}}</div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest">Languages</h6>
                                    <div class="text-xs mt-1">{{booking.user.languages ? booking.user.languages : 'N/A'}}</div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest">Work</h6>
                                    <div class="text-xs mt-1">{{booking.user.work ? booking.user.work : 'N/A'}}</div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest">School</h6>
                                    <div class="text-xs mt-1">{{booking.user.school ? booking.user.school : 'N/A'}}</div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest">About</h6>
                                    <div class="text-xs mt-1">{{booking.user.about ? booking.user.about : 'N/A'}}</div>
                                </div>
                                <div class="mt-8 flex justify-center items-center">
                                    <div class="shadow-lg px-4 py-1 rounded-full border border-gray-100 w-32 text-center
                                                cursor-pointer font-boldnosans text-gray-800"
                                            @click="toggleBio()">
                                        Hide Bio <span><i class="fas fa-chevron-up"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="pt-8">
                                <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-600 tracking-widest mb-2">
                                    Reviews from hosts
                                </h6>
                                <users-reviews-from-hosts :userId="booking.user.id"></users-reviews-from-hosts>
                            </div>
                            <div class="pt-12">
                                <h6 class="uppercase font-bold font-boldnosans text-sm 
                                        text-gray-600 tracking-widest mb-2">
                                    Reviews from renters
                                </h6>
                                <users-reviews-from-renters :userId="booking.user.id"></users-reviews-from-renters>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import { monthDayYearNumbericSlash, humanReadableDate } from './../../shared/utils/dateFormats';
    import { dollarFormat } from './../../shared/utils/currency';
    import moment from 'moment';
    import UsersReviewsFromHosts from './../../review/UsersReviewsFromHosts.vue';
    import UsersReviewsFromRenters from './../../review/UsersReviewsFromRenters.vue';

    export default {
        components: {
            UsersReviewsFromRenters,
            UsersReviewsFromHosts
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            userIsRenter() {
                return this.booking.userIs === 'renter';
            },

            userIsHost() {
                return this.booking.userIs === 'host';
            }
        },

        data() {
            return {
                loading: false,
                booking: null,
                userRating: null,
                forbidden: null,
                showBio: false
            }
        },

        methods: {
            async fetchBooking() {
                try {
                    this.loading = true;
                    let responseBooking = await axios.get(`/api/dashboard/show-booking/${this.$route.params.id}`);
                    this.booking = responseBooking.data.data;
                    await this.fetchUserRating();
                } catch (error) {
                    if (error.response.status === 403) {
                        this.forbidden = true;
                    }
                }
                this.loading = false;
            },

            async fetchUserRating() {
                try {
                    let response = await axios.get(`/api/show-review-rating/${this.booking.user.id}`);
                    this.userRating = response.data.total;
                } catch (error) {
                    console.log(error);
                }
            },

            formatDate(date) {
                return monthDayYearNumbericSlash(date);
            },

            humanReadableDate(date) {
                return humanReadableDate(date);
            },

            currencyFormat(amount) {
                return dollarFormat(amount);
            },

            bookingDateCount() {
                let from = moment(this.booking.booking.from);
                let to = moment(this.booking.booking.to);
                return moment.duration(to.diff(from)).days() + 1;
            },

            toggleBio() {
                this.showBio = !this.showBio;
            }
        },

        created() {
            this.fetchBooking();
        },
    }
</script>