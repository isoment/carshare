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
                <div class="shadow-md border border-gray-100 col-span-3 p-6 rounded-md mb-6 md:mb-0">
                    <h5 class="font-bold font-boldnosans text-lg text-gray-700">Bookings by month</h5>
                    <canvas id="barChart"></canvas>
                </div>
                <div class="shadow-md border border-gray-100 col-span-2 p-6 rounded-md">
                    <h5 class="font-bold font-boldnosans text-lg text-gray-700">Total by month</h5>
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
            <!-- Recent Orders -->
            <h5 class="font-bold font-boldnosans text-lg text-gray-700 mt-6">Recent Bookings</h5>
            <div class="bg-white rounded-md w-full">
                <div>
                    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 pt-3 pb-4 overflow-x-auto">
                        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            products
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Created at
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            QRT
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <img class="w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.2&w=160&h=160&q=80"
                                                        alt="" />
                                                </div>
                                                    <div class="ml-3">
                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                            Vera Carpenter
                                                        </p>
                                                    </div>
                                                </div>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">Admin</p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                Jan 21, 2020
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                43
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <span
                                                class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Activo</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <img class="w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.2&w=160&h=160&q=80"
                                                        alt="" />
                                                </div>
                                                    <div class="ml-3">
                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                            Blake Bowman
                                                        </p>
                                                    </div>
                                                </div>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">Editor</p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                Jan 01, 2020
                                            </p>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                77
                                            </p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <span
                                                class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Activo</span>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';
    import ChartDataLabels from 'chartjs-plugin-datalabels';
    import { dollarFormat } from '../../shared/utils/currency';

    export default {
        props: ['stats'],

        methods: {
            moneyFormat(value) {
                return dollarFormat(value);
            },

            createBarChart() {
                var ctx = document.getElementById('barChart').getContext('2d');
                var barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.stats.bookingCountByMonth.month,
                        datasets: [{
                            label: 'Bookings',
                            data: this.stats.bookingCountByMonth.count,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 206, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(153, 102, 255)',
                                'rgb(255, 159, 64)',
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

            createDonutChart() {
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
                                'rgb(148,255,212)',
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
                                        minimumFractionDigits: 2
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
            this.createBarChart();
            this.createDonutChart();
        }
    }
</script>