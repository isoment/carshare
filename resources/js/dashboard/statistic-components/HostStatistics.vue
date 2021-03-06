<template>
    <div>
        <div>
            <div>
                <h3 class="text-2xl font-bold font-boldnosans mb-3">Host Statistics</h3>
            </div>
            <!-- Basic stats -->
            <div class="flex flex-col sm:flex-row md:justify-between text-white">
                <div class="bg-gradient-to-r from-indigo-400 via-indigo-400 to-blue-400 
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
                <div class="bg-gradient-to-r from-blue-400 via-indigo-400 to-green-400 
                            w-full sm:ml-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Booking durations (days)</h4>
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
            <!-- Graph row one -->
            <div class="grid grid-cols-1 lg:grid-cols-5 lg:gap-6 mt-6 graph-row-host-stats">
                <div class="shadow-md border border-gray-100 col-span-3 p-4 rounded-md mb-6 md:mb-0">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-bold font-boldnosans text-lg text-gray-700">
                            {{ barToggle ? 'Booking durations' : 'Booking count'}}
                        </h5>
                        <label for="bar-graph-toggle" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox"
                                    id="bar-graph-toggle" 
                                    class="sr-only"
                                    v-model="barToggle">
                                <div class="bar-graph-toggle-bg block bg-gray-200 w-10 h-5 rounded-sm shadow-sm"></div>
                                <div class="bar-graph-toggle-dot absolute left-2 top-1 bg-white w-3 
                                            h-3 transition"></div>
                            </div>
                        </label>
                    </div>
                    <div>
                        <host-bookings-duration-graph v-if="barToggle"
                                                      :stats="stats.durationOfBookings">
                        </host-bookings-duration-graph>
                        <host-bookings-by-month-graph v-else 
                                                      :stats="stats.highestBookedMonths">
                        </host-bookings-by-month-graph>
                    </div>
                </div>
                <div class="shadow-md border border-gray-100 col-span-2 p-4 rounded-md">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-bold font-boldnosans text-lg text-gray-700">
                            {{pieToggle ? 'Popular vehicles' : 'Earning by month'}}
                        </h5>
                        <label for="pie-graph-toggle" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" 
                                    id="pie-graph-toggle" 
                                    class="sr-only"
                                    v-model="pieToggle">
                                <div class="pie-graph-toggle-bg block bg-gray-200 w-10 h-5 rounded-sm shadow-sm"></div>
                                <div class="pie-graph-toggle-dot absolute left-2 top-1 bg-white w-3 
                                            h-3 transition"></div>
                            </div>
                        </label>
                    </div>
                    <div>
                        <host-popular-vehicles-graph v-if="pieToggle"
                                                     :stats="stats.popularVehicles">
                        </host-popular-vehicles-graph>
                        <host-earnings-by-month-graph v-else
                                                      :stats="stats.earningsByMonth">
                        </host-earnings-by-month-graph>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <h5 class="font-bold font-boldnosans text-lg text-gray-700 mt-6">Recently Booked</h5>
            <recent-bookings-table :bookings="stats.recentBookings"></recent-bookings-table>
        </div>
    </div>
</template>

<script>
    import { dollarFormat } from '../../shared/utils/currency';
    import HostBookingsByMonthGraph from './HostBookingsByMonthGraph';
    import HostBookingsDurationGraph from './HostBookingsDurationGraph';
    import HostEarningsByMonthGraph from './HostEarningsByMonthGraph';
    import HostPopularVehiclesGraph from './HostPopularVehiclesGraph';
    import RecentBookingsTable from './RecentBookingsTable';

    export default {
        props: ['stats'],

        components: {
            HostBookingsByMonthGraph,
            HostBookingsDurationGraph,
            HostEarningsByMonthGraph,
            HostPopularVehiclesGraph,
            RecentBookingsTable
        },

        data() {
            return {
                barToggle: false,
                pieToggle: false
            }
        },

        methods: {
            moneyFormat(value) {
                return dollarFormat(value);
            }
        },
    }
</script>

<style scoped>
    input:checked ~ .bar-graph-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .bar-graph-toggle-bg {
        background-color: rgb(167, 134, 255);
    }

    input:checked ~ .pie-graph-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .pie-graph-toggle-bg {
        background-color: rgb(167, 134, 255);
    }

    /* .graph-row-host-stats {
        min-height: 29rem;
    } */
</style>