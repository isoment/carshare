<template>
    <div>
        <canvas id="chart"></canvas>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';

    export default {
        mounted() {
            let ctx = document.getElementById('chart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['A', 'B', 'C', 'D', 'E'],
                    datasets: [
                        {
                            label: 'Unfilled',
                            fill: false,
                            backgroundColor: 'rgb(179,255,191)',
                            borderColor: 'rgb(179,255,191)',
                            data: [3,7,9,2,5],
                        }, 
                        {
                            label: 'Dashed',
                            fill: false,
                            backgroundColor: 'rgb(141, 188, 247)',
                            borderColor: 'rgb(141, 188, 247)',
                            borderDash: [5, 5],
                            data: [10,2,3,9,6],
                        }, 
                        {
                            label: 'Filled',
                            backgroundColor: 'rgb(120,138,255)',
                            borderColor: 'rgb(120,138,255)',
                            data: [8,4,6,11,2],
                            // fill: true,
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Month'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                            display: true,
                            text: 'Value'
                            }
                        }
                    }
                }
            });
        }
    }
</script>