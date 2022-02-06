<template>
    <div>
        <div>
            <div>
                <h3 class="text-2xl font-bold font-boldnosans mb-3">Renter Statistics</h3>
            </div>
            <!-- Basic stats -->
            <div class="flex flex-col sm:flex-row md:justify-between text-white">
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full mr-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Total</h4>
                    <h2 class="text-3xl font-bold font-boldnosans mt-3">
                        {{moneyFormat(stats.basic.totalSpent)}}
                    </h2>
                    <h5 class="mt-6">The total you have spent on bookings</h5>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full sm:ml-2 sm:mr-2 rounded-md py-8 px-4 relative mb-3 sm:mb-0">
                    <h4>Count</h4>
                    <div class="flex justify-between mt-3">
                        <div>
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.bookingCount}}</h2>
                            <h5 class="mt-3">Bookings</h5>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold font-boldnosans">{{stats.basic.cancelCount}}</h2>
                            <h5 class="mt-3">Cancellations</h5>
                        </div>
                    </div>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
                <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                            w-full sm:ml-2 rounded-md py-8 px-4 relative">
                    <h4>{{stats.basic.orderCount}} Orders</h4>
                    <h2 class="text-3xl font-bold font-boldnosans mt-3">{{moneyFormat(stats.basic.orderAverage)}}</h2>
                    <h5 class="mt-6">The average amount per order</h5>
                    <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                </div>
            </div>
            <!-- Graphs -->
            <div class="grid grid-cols-1 md:grid-cols-5 md:gap-6 mt-6">
                <div class="shadow-md border border-gray-100 col-span-3 p-4 rounded-md mb-6 md:mb-0">
                    <h5 class="font-bold font-boldnosans text-lg text-gray-700">Bookings by month</h5>
                    <canvas id="barChart"></canvas>
                </div>
                <div class="shadow-md border border-gray-100 col-span-2 p-4 rounded-md">
                    <h5 class="font-bold font-boldnosans text-lg text-gray-700">Total by month</h5>
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
            <!-- Recent Orders -->
            <h5 class="font-bold font-boldnosans text-lg text-gray-700 mt-6">Recently Booked</h5>
            <recent-bookings-table :bookings="stats.recentBookings"></recent-bookings-table>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';
    import ChartDataLabels from 'chartjs-plugin-datalabels';
    import { dollarFormat } from '../../shared/utils/currency';
    import RecentBookingsTable from './RecentBookingsTable';

    export default {
        components: {
            RecentBookingsTable
        },

        props: ['stats'],

        methods: {
            moneyFormat(value) {
                return dollarFormat(value);
            },

            bookingsByMonthChart() {
                var ctx = document.getElementById('barChart').getContext('2d');
                var barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.stats.bookingCountByMonth.month,
                        datasets: [{
                            label: 'Bookings',
                            data: this.stats.bookingCountByMonth.count,
                            backgroundColor: [
                                'rgb(179,229,255)',
                                'rgb(54, 162, 235)',
                                'rgb(179,255,191)',
                                'rgb(75, 192, 192)',
                                'rgb(153, 102, 255)',
                                'rgb(187,255,153)',
                                'rgb(175,156,255)',
                                'rgb(120,138,255)'
                            ],
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            },

            totalsByMonthChart() {
                var ctx = document.getElementById('donutChart').getContext('2d');
                var donutChart = new Chart(ctx, {
                    type: 'pie',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: this.stats.totalsByMonth.month,
                        datasets: [{
                            data: this.stats.totalsByMonth.total,
                            backgroundColor: [
                                'rgb(175,156,255)',
                                'rgb(120,138,255)',
                                'rgb(66, 255, 180)',
                                'rgb(111,59,255)',
                                'rgb(158,255,168)',
                                'rgb(75, 192, 192)'
                                ],
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                formatter: function(value, context) {
                                    return new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD',
                                        minimumFractionDigits: 0
                                    }).format(value);
                                },
                                color: '#fff'
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
            }
        },

        mounted() {
            this.bookingsByMonthChart();
            this.totalsByMonthChart();
        }
    }
</script>