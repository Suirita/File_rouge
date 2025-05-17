<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
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
  avgScoreByBranch: {
    title: string;
    average_score: number;
  };
  lastInterviews: {
    id: number;
    candidate: {
      name: string;
    };
    status: string;
    updated_at: string;
  }[];
}>();

const totalInterviews = props.metrics.totalInterviews;
const completedInterviews = props.metrics.completedInterviews;
const averageScore = props.metrics.averageScore;
const AbsentRate = props.metrics.AbsentRate;

// Add year filter state
const currentYear = new Date().getFullYear();
const selectedYear = ref(currentYear.toString());
const years = ref([
  currentYear.toString(),
  (currentYear - 1).toString(),
  (currentYear - 2).toString(),
  (currentYear - 3).toString(),
]);

// Calculate completion percentage
const completionPercentage =
  totalInterviews > 0
    ? Math.round((completedInterviews / totalInterviews) * 100)
    : 0;

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

const branchLabels = Object.values(props.avgScoreByBranch).map(
  (b: any) => b.title,
);
const branchScores = Object.values(props.avgScoreByBranch).map(
  (b: any) => b.average_score,
);

const formatDate = (iso: string) => {
  const dt = new Date(iso);
  return dt.toLocaleString('fr-FR', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });
};

function handleYearChange(year: string) {
  router.get(
    // this is the actual current URL path (e.g. "/dashboard")
    page.url.split('?')[0],
    { year },
    {
      preserveState: true, // keeps your other props intact
      replace: true, // avoids adding a new history entry
    },
  );
}
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex justify-end px-2 pt-2">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-muted-foreground">{{
          $t('dashboard.filterByYear')
        }}</span>
        <Select
          v-model="selectedYear"
          @update:modelValue="handleYearChange"
        >
          <SelectTrigger class="w-[120px]">
            <div class="flex items-center gap-2">
              <CalendarIcon class="h-4 w-4 text-muted-foreground" />
              <SelectValue :placeholder="currentYear.toString()" />
            </div>
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="year in years"
              :key="year"
              :value="year"
            >
              {{ year }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <div
      class="grid grid-cols-1 gap-6 px-6 py-4 sm:grid-cols-1 lg:grid-cols-3"
    >
      <!-- Completed Interviews Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-blue-50 to-blue-100 pb-2 dark:from-blue-950 dark:to-blue-900"
        >
          <div>
            <CardTitle
              class="text-sm font-medium text-muted-foreground"
              >{{
                t(
                  'dashboard.kpi.completedParticipations.CompletedParticipations',
                )
              }}</CardTitle
            >
            <p class="mt-1 text-2xl font-bold">
              {{ completedInterviews }}
              <span class="text-sm font-normal text-muted-foreground"
                >{{ t('dashboard.kpi.completedParticipations.of') }}
                {{ totalInterviews }}</span
              >
            </p>
          </div>
          <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-800">
            <CheckCircle
              class="h-5 w-5 text-blue-600 dark:text-blue-300"
            />
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
            <span class="text-sm font-medium"
              >{{ completionPercentage }}%</span
            >
          </div>
          <p class="mt-2 text-xs text-muted-foreground">
            {{ totalInterviews - completedInterviews }}
            {{
              $t(
                'dashboard.kpi.completedParticipations.participationsRemaining',
              )
            }}
          </p>
        </CardContent>
      </Card>

      <!-- Average Score Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-amber-50 to-amber-100 pb-2 dark:from-amber-950 dark:to-amber-900"
        >
          <div>
            <CardTitle
              class="text-sm font-medium text-muted-foreground"
            >
              {{ $t('dashboard.kpi.averageScore.averageScore') }}
            </CardTitle>
            <p class="mt-1 flex items-baseline text-2xl font-bold">
              <span :class="getScoreColor(averageScore)">{{
                averageScore.toFixed(1)
              }}</span>
              <span
                class="ml-1 text-sm font-normal text-muted-foreground"
                >/ 5.0</span
              >
            </p>
          </div>
          <div
            class="rounded-full bg-amber-100 p-2 dark:bg-amber-800"
          >
            <Award
              class="h-5 w-5 text-amber-600 dark:text-amber-300"
            />
          </div>
        </CardHeader>
        <CardContent class="pt-4">
          <div class="flex items-center space-x-2">
            <div class="h-2 w-full rounded-full bg-amber-100">
              <div
                class="h-2 rounded-full bg-amber-500 transition-all duration-500"
                :style="{
                  width: `${(averageScore / 5) * 100}%`,
                }"
              ></div>
            </div>
            <span class="text-sm font-medium"
              >{{ Math.round((averageScore / 5) * 100) }}%</span
            >
          </div>
          <p class="mt-2 text-xs text-muted-foreground">
            {{ $t('dashboard.kpi.averageScore.basedOn') }}
            {{ completedInterviews }}
            {{
              $t('dashboard.kpi.averageScore.completedParticipations')
            }}
          </p>
        </CardContent>
      </Card>

      <!-- Absent Rate Card -->
      <Card class="overflow-hidden shadow-sm">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 bg-gradient-to-r from-rose-50 to-rose-100 pb-2 dark:from-rose-950 dark:to-rose-900"
        >
          <div>
            <CardTitle
              class="text-sm font-medium text-muted-foreground"
            >
              {{ t('dashboard.kpi.absentRate.absentCandidatesRate') }}
            </CardTitle>
            <p class="mt-1 text-2xl font-bold">
              <span :class="getAbsentRateColor(AbsentRate)"
                >{{ AbsentRate }}%</span
              >
            </p>
          </div>
          <div class="rounded-full bg-rose-100 p-2 dark:bg-rose-800">
            <UserX class="h-5 w-5 text-rose-600 dark:text-rose-300" />
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
            {{
              $t(
                'dashboard.kpi.absentRate.candidatesMissedTheirParticipations',
              )
            }}
          </p>
        </CardContent>
      </Card>

      <!-- Latest Interviews Table -->
      <Card class="shadow-sm lg:col-span-2">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 border-b pb-4"
        >
          <div class="flex items-center">
            <Clock class="mr-2 h-5 w-5 text-muted-foreground" />
            <CardTitle>
              {{
                $t(
                  'dashboard.latestParticipations.latestParticipations',
                )
              }}
            </CardTitle>
          </div>
          <Button class="text-primary-foreground" size="sm">
            {{ $t('dashboard.latestParticipations.viewAll') }}
            <ChevronRight class="h-3 w-3" />
          </Button>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>
                  {{
                    $t(
                      'dashboard.latestParticipations.table.candidate',
                    )
                  }}
                </TableHead>
                <TableHead class="text-center">
                  {{
                    $t('dashboard.latestParticipations.table.status')
                  }}
                </TableHead>
                <TableHead class="text-right">
                  {{
                    $t('dashboard.latestParticipations.table.date')
                  }}
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="interview in lastInterviews"
                :key="interview.id"
                class="hover:bg-muted/50"
              >
                <TableCell class="font-medium">{{
                  interview.candidate.name
                }}</TableCell>
                <TableCell class="text-center">
                  <span
                    class="rounded-full bg-green-500 px-2 py-1 text-xs font-medium"
                  >
                    {{ interview.status }}
                  </span>
                </TableCell>
                <TableCell
                  class="text-right text-xs text-muted-foreground"
                >
                  {{ formatDate(interview.updated_at) }}
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Average Score by Branch Chart -->
      <Card class="shadow-sm lg:col-span-1">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 border-b pb-4"
        >
          <div class="flex items-center">
            <BarChart3 class="mr-2 h-5 w-5 text-muted-foreground" />
            <CardTitle>
              {{ $t('dashboard.averageScoreByBranch') }}</CardTitle
            >
          </div>
        </CardHeader>
        <CardContent class="pt-6">
          <BarChart
            :labels="branchLabels"
            :data="branchScores"
            :max-score="5"
          />
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
