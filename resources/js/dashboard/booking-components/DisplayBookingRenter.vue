<template>
    <div>
        <div v-if="hasBookings">
            <h4 class="font-bold text-lg font-boldnosans text-gray-700 mb-2">
                Vehicles you have booked
            </h4>
            <div v-for="booking in bookings.data" :key="booking.id">
                <div class="mb-2 border border-gray-200 rounded-sm py-3 px-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="flex items-start">
                                <div class="h-20 w-32 rounded-sm"
                                     :style="{ 'background-image': 'url(' + booking.vehicle.image + ')' }"
                                     style="background-size: cover; background-position: 50% 50%;">
                                </div>
                                <div class="ml-3">
                                    <h6 class="font-bold font-boldnosans text-gray-700">
                                        {{booking.vehicle.make}} {{booking.vehicle.model}}
                                    </h6>
                                    <h6 class="text-lg font-bold font-boldnosans text-purple-900">
                                        {{booking.vehicle.year}}
                                    </h6>
                                    <div class="text-xs font-light text-gray-500">
                                        <div>From: 
                                            <span class="font-bold">{{formatDates(booking.booking.from)}}</span>
                                        </div>
                                        <div>To: 
                                            <span class="font-bold">{{formatDates(booking.booking.to)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="text-sm">
                                    Price / Day: <span class="font-bold text-purple-400">
                                        {{currencyFormat(booking.booking.price_day)}}
                                    </span>
                                </div>
                                <div class="text-sm">
                                    Booking Total: <span class="font-bold text-purple-400">
                                        {{currencyFormat(booking.booking.price_total)}}
                                    </span>
                                </div>
                                <div class="text-sm w-28 flex items-center mt-2 bg-purple-400 text-white px-2
                                            justify-center">
                                    <div class="mr-1">View Order</div>
                                    <div><i class="far fa-list-alt text-lg"></i></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-pen-square text-3xl text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <h4 class="font-bold text-lg font-boldnosans text-gray-700 mb-2">
                No bookings
            </h4>
        </div>
    </div>
</template>

<script>
    import { monthDayYearNumbericSlash } from './../../shared/utils/dateFormats';
    import { dollarFormat } from './../../shared/utils/currency';

    export default {
        props: {
            bookings: Object
        },

        computed: {
            hasBookings() {
                return this.bookings.data.length !== 0;
            },
        },

        methods: {
            formatDates(date) {
                return monthDayYearNumbericSlash(date);
            },

            currencyFormat(price) {
                return dollarFormat(price);
            }
        }
    }
</script>

<style>

</style>