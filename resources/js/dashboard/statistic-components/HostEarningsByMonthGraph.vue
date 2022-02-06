<template>
    <div>
        <canvas id="chartTwo"></canvas>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';
    import ChartDataLabels from 'chartjs-plugin-datalabels';

    export default {
        props: ['stats'],

        mounted() {
            let ctx = document.getElementById('chartTwo').getContext('2d');
            let chartTwo = new Chart(ctx, {
                type: 'pie',
                plugins: [ChartDataLabels],
                data: {
                    labels: this.stats.month,
                    datasets: [{
                        data: this.stats.total,
                        backgroundColor: [
                            'rgb(175,156,255)',
                            'rgb(120,138,255)',
                            'rgb(66, 255, 180)',
                            'rgb(111,59,255)',
                            'rgb(158,255,168)',
                            'rgb(75, 192, 192)'
                            ],
                        hoverOffset: 4
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
    }
</script>

<style scoped>
    #chartTwo {
        max-height: 25rem;
    }
</style>