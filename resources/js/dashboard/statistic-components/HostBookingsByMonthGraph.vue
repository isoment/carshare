<template>
    <div>
        <canvas id="chart"></canvas>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';

    export default {
        props: ['stats'],

        mounted() {
            let ctx = document.getElementById('chart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.stats.month,
                    datasets: [{
                        label: 'Bookings',
                        data: this.stats.count,
                        backgroundColor: [
                            'rgb(187,255,153)',
                            'rgb(54, 162, 235)',
                            'rgb(120,138,255)',
                            'rgb(179,255,191)',
                            'rgb(179,229,255)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                            'rgb(175,156,255)'
                            
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
        }
    }
</script>