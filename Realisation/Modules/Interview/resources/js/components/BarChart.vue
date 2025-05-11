<script setup lang="ts">
import { BarElement, CategoryScale, Chart, Legend, LinearScale, Title, Tooltip } from 'chart.js';
import { defineProps, ref, watch } from 'vue';
import { Bar } from 'vue-chartjs';

// register necessary chart components
Chart.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

// Props interface
interface Props {
    labels: string[];
    data: number[];
    maxScore?: number;
}
const props = defineProps<Props>();

// Reactive chart data and options
const chartData = ref({
    labels: props.labels,
    datasets: [
        {
            data: props.data,
            backgroundColor: '#FF9900',
            barPercentage: 0.6,
            categoryPercentage: 0.6,
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        title: { display: false },
    },
    scales: {
        y: {
            beginAtZero: true,
            suggestedMax: props.maxScore ?? 5,
            ticks: {
                stepSize: 1,
            },
        },
    },
});

// Watch for prop changes and update chart data
watch(
    () => [props.labels, props.data],
    () => {
        chartData.value.labels = props.labels;
        chartData.value.datasets[0].data = props.data;
    },
);
</script>

<template>
    <div class="h-64 w-full">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>
