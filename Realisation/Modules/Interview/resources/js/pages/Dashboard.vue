<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Award, BarChart3, CheckCircle, ChevronRight, Clock, UserX } from 'lucide-vue-next';
import BarChart from '../components/BarChart.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tableau de bord',
        href: '/dashboard',
    },
];

const props = defineProps<{
    metrics: {
        totalInterviews: number;
        completedInterviews: number;
        averageScore: number;
        AbsentRate: number;
    };
    avgScoreByBranch: any;
    lastInterviews: any;
}>();

console.log(props.avgScoreByBranch);

const totalInterviews = props.metrics.totalInterviews;
const completedInterviews = props.metrics.completedInterviews;
const averageScore = props.metrics.averageScore;
const AbsentRate = props.metrics.AbsentRate;

// Calculate completion percentage
const completionPercentage = totalInterviews > 0 ? Math.round((completedInterviews / totalInterviews) * 100) : 0;

// Determine score color based on value (1-5 scale)
const getScoreColor = (score: number) => {
    if (score >= 4) return 'text-emerald-500';
    if (score >= 3) return 'text-amber-500';
    return 'text-rose-500';
};

// Determine absent rate color
const getAbsentRateColor = (rate: number) => {
    if (rate <= 5) return 'text-emerald-500';
    if (rate <= 15) return 'text-amber-500';
    return 'text-rose-500';
};

const branchLabels = Object.values(props.avgScoreByBranch).map((b) => b.title);
const branchScores = Object.values(props.avgScoreByBranch).map((b) => b.average_score);

console.log(branchLabels);
console.log(branchScores);

const formatDate = (iso: string) => {
    const dt = new Date(iso);
    return dt.toLocaleString('fr-FR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
};

// Get status badge color
const getStatusColor = (status: string) => {
    const statusMap: Record<string, string> = {
        completed: 'bg-emerald-100 text-emerald-800',
        scheduled: 'bg-blue-100 text-blue-800',
        cancelled: 'bg-rose-100 text-rose-800',
        absent: 'bg-amber-100 text-amber-800',
        pending: 'bg-gray-100 text-gray-800',
    };

    return statusMap[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-1 lg:grid-cols-3">
            <!-- KPI Cards -->
            <Card class="overflow-hidden shadow-sm">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-blue-50 to-blue-100 pb-2 dark:from-blue-950 dark:to-blue-900"
                >
                    <div>
                        <CardTitle class="text-sm font-medium text-muted-foreground">Completed Interviews</CardTitle>
                        <p class="mt-1 text-2xl font-bold">
                            {{ completedInterviews }} <span class="text-sm font-normal text-muted-foreground">of {{ totalInterviews }}</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-800">
                        <CheckCircle class="h-5 w-5 text-blue-600 dark:text-blue-300" />
                    </div>
                </CardHeader>
                <CardContent class="pt-4">
                    <div class="flex items-center space-x-2">
                        <div class="h-2 w-full rounded-full bg-blue-100">
                            <div
                                class="h-2 rounded-full bg-blue-600 transition-all duration-500"
                                :style="{ width: `${completionPercentage}%` }"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ completionPercentage }}%</span>
                    </div>
                    <p class="mt-2 text-xs text-muted-foreground">{{ totalInterviews - completedInterviews }} interviews remaining</p>
                </CardContent>
            </Card>

            <Card class="overflow-hidden shadow-sm">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-amber-50 to-amber-100 pb-2 dark:from-amber-950 dark:to-amber-900"
                >
                    <div>
                        <CardTitle class="text-sm font-medium text-muted-foreground">Average Score</CardTitle>
                        <p class="mt-1 flex items-baseline text-2xl font-bold">
                            <span :class="getScoreColor(averageScore)">{{ averageScore.toFixed(1) }}</span>
                            <span class="ml-1 text-sm font-normal text-muted-foreground">/ 5.0</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-amber-100 p-2 dark:bg-amber-800">
                        <Award class="h-5 w-5 text-amber-600 dark:text-amber-300" />
                    </div>
                </CardHeader>
                <CardContent class="pt-4">
                    <div class="flex items-center space-x-2">
                        <div class="h-2 w-full rounded-full bg-amber-100">
                            <div
                                class="h-2 rounded-full bg-amber-500 transition-all duration-500"
                                :style="{ width: `${(averageScore / 5) * 100}%` }"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ Math.round((averageScore / 5) * 100) }}%</span>
                    </div>
                    <p class="mt-2 text-xs text-muted-foreground">Based on {{ completedInterviews }} completed interviews</p>
                </CardContent>
            </Card>

            <Card class="overflow-hidden shadow-sm">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-rose-50 to-rose-100 pb-2 dark:from-rose-950 dark:to-rose-900"
                >
                    <div>
                        <CardTitle class="text-sm font-medium text-muted-foreground">Absent Candidates Rate</CardTitle>
                        <p class="mt-1 text-2xl font-bold">
                            <span :class="getAbsentRateColor(AbsentRate)">{{ AbsentRate }}%</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-rose-100 p-2 dark:bg-rose-800">
                        <UserX class="h-5 w-5 text-rose-600 dark:text-rose-300" />
                    </div>
                </CardHeader>
                <CardContent class="pt-4">
                    <div class="flex items-center space-x-2">
                        <div class="h-2 w-full rounded-full bg-rose-100">
                            <div class="h-2 rounded-full bg-rose-500 transition-all duration-500" :style="{ width: `${AbsentRate}%` }"></div>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-muted-foreground">
                        {{ Math.round(totalInterviews * (AbsentRate / 100)) }} candidates missed their interviews
                    </p>
                </CardContent>
            </Card>

            <Card class="shadow-sm lg:col-span-2">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 border-b pb-4">
                    <div class="flex items-center">
                        <Clock class="mr-2 h-5 w-5 text-muted-foreground" />
                        <CardTitle>Latest Interviews</CardTitle>
                    </div>
                    <Button class="text-white" size="sm">
                        View All
                        <ChevronRight class="h-3 w-3" />
                    </Button>
                </CardHeader>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-xs">Candidate</TableHead>
                                <TableHead class="text-xs">Status</TableHead>
                                <TableHead class="text-xs">Date</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="interview in lastInterviews" :key="interview.id" class="hover:bg-muted/50">
                                <TableCell class="font-medium">{{ interview.candidate.name }}</TableCell>
                                <TableCell>
                                    <span :class="`rounded-full px-2 py-1 text-xs font-medium ${getStatusColor(interview.status)}`">
                                        {{ interview.status }}
                                    </span>
                                </TableCell>
                                <TableCell class="text-xs text-muted-foreground">{{ formatDate(interview.updated_at) }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

            <Card class="shadow-sm lg:col-span-1">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 border-b pb-4">
                    <div class="flex items-center">
                        <BarChart3 class="mr-2 h-5 w-5 text-muted-foreground" />
                        <CardTitle>Average Score by Branch</CardTitle>
                    </div>
                </CardHeader>
                <CardContent class="pt-6">
                    <BarChart :labels="branchLabels" :data="branchScores" :max-score="5" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
