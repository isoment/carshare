<template>
    <div>
        <main-navigation></main-navigation>
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            <div class="mt-8">
                <div class="flex items-center justify-end mb-4">
                    <span class="mr-2 font-bold font-boldnosans text-gray-700">As Host:</span>
                    <label for="booking-stat-toggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" 
                                id="booking-stat-toggle" 
                                class="sr-only"
                                v-model="toggle">
                            <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-7 rounded-full"></div>
                            <div class="booking-stat-toggle-dot absolute left-2 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <!-- Basic stats -->
                <div class="flex justify-between text-white">
                    <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                                w-full mr-2 rounded-md py-8 px-4 relative">
                        <h4>Total</h4>
                        <h2 class="text-3xl font-bold font-boldnosans mt-3">$12,653.65</h2>
                        <h5 class="mt-6">The total you have spent on bookings</h5>
                        <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                    </div>
                    <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                                w-full ml-2 mr-2 rounded-md py-8 px-4 relative">
                        <h4>Count</h4>
                        <div class="flex justify-between mt-3">
                            <div>
                                <h2 class="text-3xl font-bold font-boldnosans">12</h2>
                                <h5 class="mt-3">Bookings</h5>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold font-boldnosans">1</h2>
                                <h5 class="mt-3">Cancellations</h5>
                            </div>
                        </div>
                        <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                    </div>
                    <div class="bg-gradient-to-r from-purple-400 via-purple-400 to-indigo-400 
                                w-full ml-2 rounded-md py-8 px-4 relative">
                        <h4>14 Orders</h4>
                        <h2 class="text-3xl font-bold font-boldnosans mt-3">$756.65</h2>
                        <h5 class="mt-6">The average amount per order</h5>
                        <img src="/img/circle.svg" alt="circle" class="h-48 w-48 absolute bottom-0 -right-5">
                    </div>
                </div>
                <!-- Graphs -->
                <div class="grid grid-cols-5 gap-6 mt-6">
                    <div class="shadow-md border border-gray-100 col-span-3 p-6 rounded-md">
                        <h5 class="font-bold font-boldnosans text-lg text-gray-700">Bookings by month</h5>
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="shadow-md border border-gray-100 col-span-2 p-6 rounded-md">
                        <h5 class="font-bold font-boldnosans text-lg text-gray-700">Donut chart</h5>
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
                <!-- Recent Orders -->
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto'

    export default {
        data() {
            return {
                toggle: false
            }
        },

        methods: {
            createBarChart() {
                var ctx = document.getElementById('barChart').getContext('2d');
                var barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                        datasets: [{
                            label: '# of Votes',
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },

            createDonutChart() {
                var ctx = document.getElementById('donutChart').getContext('2d');
                var donutChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Green', 'Purple', 'Orange'],
                        datasets: [{
                            label: '# of Donuts',
                            data: [300, 50, 100],
                            backgroundColor: [
                                'rgb(175,156,255)',
                                'rgb(120,138,255)',
                                'rgb(148,255,212)'
                                ],
                            hoverOffset: 4
                        }]
                    },
                });
            }
        },

        mounted() {
            this.createBarChart();
            this.createDonutChart();
        }
    }
</script>

<style scoped>
    input:checked ~ .booking-stat-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .booking-stat-toggle-bg {
        background-color: rgb(167, 134, 255);
    }
</style>