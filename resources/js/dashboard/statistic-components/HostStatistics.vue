<template>
    <div>
        <div>
            <div>
                <h3 class="text-2xl font-bold font-boldnosans mb-3">Host Statistics</h3>
            </div>
            <!-- Basic stats -->
            <div class="flex flex-col sm:flex-row md:justify-between text-white">
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full mr-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Total</h4>
                    <h2 class="text-3xl font-bold font-boldnosans mt-3">
                        {{moneyFormat(stats.basic.totalEarned)}}
                    </h2>
                    <h5 class="mt-6">The total your vehicle have earned you</h5>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full sm:ml-2 sm:mr-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Your vehicles</h4>
                    <div class="flex justify-between mt-3">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.bookingCount}}</h2>
                            <h5 class="mt-3">Bookings</h5>
                        </div>
                        <div class="text-center">
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.cancelCount}}</h2>
                            <h5 class="mt-3">You cancelled</h5>
                        </div>
                    </div>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full sm:ml-2 sm:mr-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Booking durations</h4>
                    <div class="flex justify-between mt-3">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.bookingAverage}}</h2>
                            <h5 class="mt-3">Average</h5>
                        </div>
                        <div class="text-center">
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.longestBooking}}</h2>
                            <h5 class="mt-3">Longest</h5>
                        </div>
                    </div>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { dollarFormat } from '../../shared/utils/currency';
    import { monthDayYearNumbericSlash } from '../../shared/utils/dateFormats';

    export default {
        props: ['stats'],

        methods: {
            moneyFormat(value) {
                return dollarFormat(value);
            },

            dateFormat(value) {
                return monthDayYearNumbericSlash(value);
            },
        },

        mounted() {

        }
    }
</script>