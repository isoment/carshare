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
                                    class="bg-white px-4 py-2 text-red-500 border-2 border-red-500 
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
                            <div class="mt-10">
                                <h6 class="uppercase font-bold font-boldnosans text-xs 
                                        text-gray-500 tracking-widest mb-4">
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
                                    <div href="#" class="text-2xl font-bold">
                                        {{currencyFormat(booking.booking.price_day)}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="uppercase font-bold font-boldnosans text-xs text-gray-500 tracking-widest">
                                        Total / Days:
                                    </div>
                                    <div href="#" class="text-2xl font-bold pl-20 border-b border-gray-200 pb-2">
                                        <span class="text-sm text-gray-400 font-light">x</span> {{bookingDateCount()}}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="uppercase font-bold font-boldnosans text-xs text-gray-500 tracking-widest">
                                        Price Total:
                                    </div>
                                    <div href="#" class="text-purple-500 text-2xl font-bold">
                                        {{currencyFormat(booking.booking.price_total)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right -->
                    <div>
                        TEST
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import { monthDayYearNumbericSlash } from './../../shared/utils/dateFormats';
    import { dollarFormat } from './../../shared/utils/currency';
    import moment from 'moment';

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),
        },

        data() {
            return {
                loading: false,
                booking: null,
                forbidden: null
            }
        },

        methods: {
            async fetchBooking() {
                try {
                    this.loading = true;
                    let response = await axios.get(`/api/dashboard/show-booking/${this.$route.params.id}`);
                    this.booking = response.data.data;
                } catch (error) {
                    console.log(error.response);
                    if (error.response.status === 403) {
                        this.forbidden = true;
                    }
                }
                this.loading = false;
            },

            formatDate(date) {
                return monthDayYearNumbericSlash(date);
            },

            currencyFormat(amount) {
                return dollarFormat(amount);
            },

            bookingDateCount() {
                let from = moment(this.booking.booking.from);
                let to = moment(this.booking.booking.to);
                return moment.duration(to.diff(from)).asDays() + 1;
            }
        },

        created() {
            this.fetchBooking();
        }
    }
</script>