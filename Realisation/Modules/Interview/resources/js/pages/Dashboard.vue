<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
  Award,
  BarChart3,
  CalendarDays,
  CheckCircle,
  ChevronRight,
  Clock,
  UserX,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import BarChart from '../components/BarChart.vue';

const { t } = useI18n();
const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('dashboard.breadcrumbs.dashboard'),
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
  avgScoreByQuestionType: {
    name: string;
    average_score: number;
  };
  lastInterviews: {
    id: number;
    candidate: {
      name: string;
    };
    status: string;
    evaluations: {
      score: number;
      remarks: string;
    }[];
    date: string;
  }[];
  years: string[];
  selectedYear: string;
}>();

const totalInterviews = props.metrics.totalInterviews;
const completedInterviews = props.metrics.completedInterviews;
const averageScore = props.metrics.averageScore;
const AbsentRate = props.metrics.AbsentRate;

const branchLabels = Object.values(props.avgScoreByQuestionType).map((b: any) => b.title);
const branchScores = Object.values(props.avgScoreByQuestionType).map((b: any) => b.average_score);

// Add year filter state
const currentYear = 2024;
const selectedYear = ref(props.selectedYear ?? currentYear.toString());
const years = ref(props.years.length ? props.years : [currentYear.toString()]);

function handleYearChange(selectedYear: string) {
  router.get(page.url.split('?')[0], { selectedYear }, { replace: true });
}

// Calculate completion percentage
const completionPercentage =
  totalInterviews > 0 ? Math.round((completedInterviews / totalInterviews) * 100) : 0;

// Determine score color based on value (1-5 scale)
const getScoreColor = (score: number) => {
  if (score >= 8) return 'text-emerald-500';
  if (score >= 5) return 'text-amber-500';
  return 'text-rose-500';
};

// Determine absent rate color
const getAbsentRateColor = (rate: number) => {
  if (rate <= 5) return 'text-emerald-500';
  if (rate <= 15) return 'text-amber-500';
  return 'text-rose-500';
};

const formatDate = (iso: string) => {
  const dt = new Date(iso);
  return dt.toLocaleString('fr-FR', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });
};

const getAvgScore = (evaluations: { score: number }[]): string => {
  if (!evaluations || evaluations.length === 0) {
    return '—'; // or "0.0" if you prefer
  }
  const sum = evaluations.reduce((acc, ev) => acc + ev.score, 0);
  const avg = sum / evaluations.length;
  // round to one decimal place and return as string
  return avg.toFixed(1);
};
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex justify-end px-2 pt-2">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-muted-foreground">
          {{ $t('dashboard.filterByYear') }}
        </span>
        <Select v-model="selectedYear" @update:modelValue="handleYearChange">
          <SelectTrigger class="w-[120px]">
            <div class="flex items-center gap-2">
              <CalendarDays class="h-4 w-4 text-muted-foreground" />
              <SelectValue :value="selectedYear" :placeholder="currentYear.toString()" />
            </div>
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="year in years" :key="year" :value="year">
              {{ year }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 px-6 py-4 sm:grid-cols-1 lg:grid-cols-3">
      <!-- Completed Interviews Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-blue-50 to-blue-100 pb-2 dark:from-blue-950 dark:to-blue-900"
        >
          <div>
            <CardTitle class="text-sm font-medium text-muted-foreground">
              {{ t('dashboard.kpi.completedInterviews.completedInterviews') }}
            </CardTitle>
            <p class="mt-1 text-2xl font-bold">
              {{ completedInterviews }}
              <span class="text-sm font-normal text-muted-foreground">
                {{ t('dashboard.kpi.completedInterviews.of') }}
                {{ totalInterviews }}
              </span>
            </p>
          </div>
          <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-800">
            <CheckCircle class="h-10 w-10 text-blue-600 dark:text-blue-300" />
          </div>
        </CardHeader>
        <CardContent class="pt-4">
          <div class="flex items-center space-x-2">
            <div class="h-2 w-full rounded-full bg-blue-100">
              <div
                class="h-2 rounded-full bg-blue-600 transition-all duration-500"
                :style="{
                  width: `${completionPercentage}%`,
                }"
              ></div>
            </div>
            <span class="text-sm font-medium">{{ completionPercentage }}%</span>
          </div>
          <p class="mt-2 text-xs text-muted-foreground">
            {{ totalInterviews - completedInterviews }}
            {{ $t('dashboard.kpi.completedInterviews.interviewsRemaining') }}
          </p>
        </CardContent>
      </Card>

      <!-- Average Score Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-amber-50 to-amber-100 pb-2 dark:from-amber-950 dark:to-amber-900"
        >
          <div>
            <CardTitle class="text-sm font-medium text-muted-foreground">
              {{ $t('dashboard.kpi.averageScore.averageScore') }}
            </CardTitle>
            <p class="mt-1 flex items-baseline text-2xl font-bold">
              <span :class="getScoreColor(averageScore)">
                {{ averageScore.toFixed(1) }}
              </span>
              <span class="ml-1 text-sm font-normal text-muted-foreground"> / 5.0 </span>
            </p>
          </div>
          <div class="rounded-full bg-amber-100 p-2 dark:bg-amber-800">
            <Award class="h-10 w-10 text-amber-600 dark:text-amber-300" />
          </div>
        </CardHeader>
        <CardContent class="pt-4">
          <div class="flex items-center space-x-2">
            <div class="h-2 w-full rounded-full bg-amber-100">
              <div
                class="h-2 rounded-full bg-amber-500 transition-all duration-500"
                :style="{
                  width: `${(averageScore / 10) * 100}%`,
                }"
              ></div>
            </div>
            <span class="text-sm font-medium"> {{ Math.round((averageScore / 10) * 100) }}% </span>
          </div>
          <p class="mt-2 text-xs text-muted-foreground">
            {{ $t('dashboard.kpi.averageScore.basedOn') }}
            {{ completedInterviews }}
            {{ $t('dashboard.kpi.averageScore.completedInterviews') }}
          </p>
        </CardContent>
      </Card>

      <!-- Absent Rate Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-rose-50 to-rose-100 pb-2 dark:from-rose-950 dark:to-rose-900"
        >
          <div>
            <CardTitle class="text-sm font-medium text-muted-foreground">
              {{ t('dashboard.kpi.absentRate.absentCandidatesRate') }}
            </CardTitle>
            <p class="mt-1 text-2xl font-bold">
              <span :class="getAbsentRateColor(AbsentRate)">{{ AbsentRate }}%</span>
            </p>
          </div>
          <div class="rounded-full bg-rose-100 p-2 dark:bg-rose-800">
            <UserX class="h-10 w-10 text-rose-600 dark:text-rose-300" />
          </div>
        </CardHeader>
        <CardContent class="pt-4">
          <div class="flex items-center space-x-2">
            <div class="h-2 w-full rounded-full bg-rose-100">
              <div
                class="h-2 rounded-full bg-rose-500 transition-all duration-500"
                :style="{
                  width: `${AbsentRate}%`,
                }"
              ></div>
            </div>
          </div>
          <p class="mt-2 text-xs text-muted-foreground">
            {{ Math.round(totalInterviews * (AbsentRate / 100)) }}
            {{ $t('dashboard.kpi.absentRate.candidatesMissedTheirInterview') }}
          </p>
        </CardContent>
      </Card>

      <!-- Latest Interviews Table -->
      <Card class="shadow-sm lg:col-span-2">
        <CardHeader class="flex flex-row items-center justify-between space-y-0 border-b pb-4">
          <div class="flex items-center">
            <Clock class="mr-2 h-5 w-5 text-muted-foreground" />
            <CardTitle>
              {{ $t('dashboard.latestInterviews.latestInterviews') }}
            </CardTitle>
          </div>
          <Button asChild size="sm" class="text-primary-foreground">
            <a href="/dashboard/interview" class="flex items-center gap-1">
              {{ t('dashboard.latestInterviews.viewAll') }}
              <ChevronRight class="h-3 w-3" />
            </a>
          </Button>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>
                  {{ $t('dashboard.latestInterviews.table.candidate') }}
                </TableHead>
                <TableHead class="text-center">
                  {{ $t('dashboard.latestInterviews.table.status') }}
                </TableHead>
                <TableHead class="text-center">
                  {{ $t('dashboard.latestInterviews.table.score') }}
                </TableHead>
                <TableHead class="text-right">
                  {{ $t('dashboard.latestInterviews.table.date') }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="interview in lastInterviews"
                :key="interview.id"
                class="hover:bg-muted/50"
              >
                <TableCell class="font-medium">{{ interview.candidate.name }}</TableCell>
                <TableCell class="text-center">
                  <span
                    :class="[
                      'rounded-full px-2 py-1 text-xs font-medium',
                      interview.status === 'completed' ? 'bg-blue-300' : 'bg-rose-400',
                    ]"
                  >
                    {{ t(`dashboard.latestInterviews.statusOptions.${interview.status}`) }}
                  </span>
                </TableCell>
                <TableCell class="text-center font-medium">
                  {{ getAvgScore(interview.evaluations) }}
                </TableCell>
                <TableCell class="text-right text-xs text-muted-foreground">
                  {{ formatDate(interview.date) }}
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Average Score by Branch Chart -->
      <Card class="shadow-sm lg:col-span-1">
        <CardHeader class="flex flex-row items-center justify-between space-y-0 border-b pb-4">
          <div class="flex items-center">
            <BarChart3 class="mr-2 h-5 w-5 text-muted-foreground" />
            <CardTitle> {{ $t('dashboard.averageScoreByQuestionType') }}</CardTitle>
          </div>
        </CardHeader>
        <CardContent class="pt-6">
          <BarChart :labels="branchLabels" :data="branchScores" :max-score="5" />
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
