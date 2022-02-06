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
                type: 'line',
                data: {
                    labels: this.stats.booking,
                    datasets: [
                        {
                            label: 'Duration',
                            fill: false,
                            borderColor: 'rgb(167, 121, 236)',
                            backgroundColor: 'rgb(179,255,191)',
                            data: this.stats.duration,
                            fill: true
                        },
                    ]
                },
                options: {
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Booking Id'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Days'
                            }
                        }
                    }
                }
            });
        }
    }
</script>