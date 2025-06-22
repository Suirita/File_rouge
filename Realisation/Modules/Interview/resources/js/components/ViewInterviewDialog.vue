<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Separator } from '@/components/ui/separator';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { CalendarIcon, FileTextIcon, TrendingUpIcon, UserIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
  open: boolean;
  interview?: {
    id: string;
    status: string;
    date: string;
    created_at: string;
    evaluations: {
      evaluator: {
        id: number;
        name: string;
      };
      question: {
        id: number;
        title: string;
      };
      score: number;
      remarks: string;
    }[];
    candidate: {
      id: number;
      name: string;
    };
    template: {
      id: number;
      title: string;
      types: {
        id: number;
        title: string;
        questions: {
          id: number;
          title: string;
          type: string;
        }[];
      }[];
    };
  };
}>();

const emit = defineEmits(['update:open']);

// Utility: compute average score from evaluations
const getAvgScore = (evs: { score: number }[]): string => {
  if (!evs || evs.length === 0) return '—';
  const sum = evs.reduce((acc, e) => acc + e.score, 0);
  return (sum / evs.length).toFixed(1);
};

// Get status variant and color
const getStatusVariant = (status: string) => {
  switch (status) {
    case 'completed':
      return 'default';
    case 'pending':
      return 'secondary';
    case 'absent':
      return 'outline';
    default:
      return 'outline';
  }
};

// Get score color based on value (1-10 scale, red to green gradient)
const getScoreColor = (score: number): string => {
  if (score >= 8) return '#84cc16'; // Green
  if (score >= 7) return '#84cc16'; // Light green
  if (score >= 6) return '#eab308'; // Yellow
  if (score >= 5) return '#f59e0b'; // Orange
  if (score >= 4) return '#f97316'; // Dark orange
  if (score >= 3) return '#ef4444'; // Red
  return '#dc2626'; // Dark red
};

// Get score background color (lighter version)
const getScoreBackgroundColor = (score: number): string => {
  if (score >= 8) return '#dcfce7'; // Light green
  if (score >= 7) return '#ecfccb'; // Very light green
  if (score >= 6) return '#fef3c7'; // Light yellow
  if (score >= 5) return '#fed7aa'; // Light orange
  if (score >= 4) return '#fed7aa'; // Light orange
  if (score >= 3) return '#fecaca'; // Light red
  return '#fecaca'; // Light red
};

// Get score text description
const getScoreDescription = (score: number): string => {
  if (score >= 9) return 'Excellent';
  if (score >= 8) return 'Very Good';
  if (score >= 7) return 'Good';
  if (score >= 6) return 'Above Average';
  if (score >= 5) return 'Average';
  if (score >= 4) return 'Below Average';
  if (score >= 3) return 'Poor';
  return 'Very Poor';
};

// Calculate average score as number
const getAvgScoreNumber = (evs: { score: number }[]): number => {
  if (!evs || evs.length === 0) return 0;
  const sum = evs.reduce((acc, e) => acc + e.score, 0);
  return sum / evs.length;
};

// Group evaluations by question_id and calculate averages
const groupedEvaluations = computed(() => {
  const groups = {};

  props.interview.evaluations.forEach((evaluation) => {
    const questionId = evaluation.question.id;
    if (!groups[questionId]) {
      groups[questionId] = {
        question_id: questionId,
        question_title: evaluation.question.title,
        evaluations: [],
        average_score: 0,
      };
    }
    groups[questionId].evaluations.push(evaluation);
  });

  // Calculate average scores for each group
  Object.values(groups).forEach((group: any) => {
    const sum = group.evaluations.reduce((acc, ev) => acc + ev.score, 0);
    group.average_score = sum / group.evaluations.length;
  });

  return Object.values(groups);
});
</script>

<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[700px] w-full max-w-3xl overflow-y-auto">
      <DialogHeader class="space-y-4 px-6 pb-2 pt-6">
        <div class="flex items-start gap-4">
          <div class="flex-1 space-y-2">
            <div class="flex items-center justify-between">
              <DialogTitle class="text-xl font-semibold text-gray-900">
                {{ interview?.candidate.name }}
              </DialogTitle>
              <Badge
                :variant="getStatusVariant(interview?.status ?? 'pending')"
                class="px-3 py-1 text-xs font-medium"
                :class="{
                  'border-blue-200 bg-amber-300 text-amber-800': interview?.status === 'pending',
                  'border-green-200 bg-blue-300 text-blue-800': interview?.status === 'completed',
                  'border-gray-200 bg-rose-400 text-rose-800': interview?.status === 'absent',
                }"
              >
                {{ t(`interview.viewInterviewDialog.status.${interview?.status}`) }}
              </Badge>
            </div>
          </div>
        </div>
      </DialogHeader>

      <Separator />

      <div class="flex-1 overflow-y-auto">
        <Tabs defaultValue="overview" class="w-full">
          <TabsList
            v-if="interview?.status == 'completed'"
            class="mb-6 grid w-full grid-cols-2 p-1"
          >
            <TabsTrigger value="overview">
              <div class="flex items-center gap-2">
                <UserIcon class="h-4 w-4" />
                {{ t('interview.viewInterviewDialog.overview') }}
              </div>
            </TabsTrigger>
            <TabsTrigger value="evaluations">
              <div class="flex items-center gap-2">
                <TrendingUpIcon class="h-4 w-4" />
                {{ t('interview.viewInterviewDialog.evaluations') }}
              </div>
            </TabsTrigger>
          </TabsList>

          <TabsContent value="overview" class="space-y-6">
            <!-- Score Overview Card -->
            <div
              v-if="interview?.status == 'completed'"
              class="from-soft-blue/5 to-warm-orange/5 rounded-lg border bg-gradient-to-r p-6"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="mb-1 text-sm font-medium text-gray-600">
                    {{ t('interview.viewInterviewDialog.score') }}
                  </h4>
                  <div class="flex items-center gap-3">
                    <span class="text-3xl font-bold text-gray-900">
                      {{ getAvgScore(interview.evaluations) }}
                    </span>
                    <span class="text-sm text-gray-500">/10</span>
                  </div>
                  <div class="mt-2">
                    <span
                      class="rounded-full px-2 py-1 text-sm font-medium"
                      :style="{
                        color: getScoreColor(getAvgScoreNumber(interview.evaluations)),
                        backgroundColor: getScoreBackgroundColor(
                          getAvgScoreNumber(interview.evaluations),
                        ),
                      }"
                    >
                      {{ getScoreDescription(getAvgScoreNumber(interview.evaluations)) }}
                    </span>
                  </div>
                </div>
                <div class="text-right">
                  <div class="mb-2 text-sm text-gray-600">
                    {{ interview.evaluations.length }}
                    {{ t('interview.viewInterviewDialog.questions') }}
                  </div>
                  <!-- Score visualization circle -->
                  <div class="relative h-16 w-16">
                    <svg class="h-16 w-16 -rotate-90 transform" viewBox="0 0 36 36">
                      <!-- Background circle -->
                      <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none"
                        stroke="#e5e7eb"
                        stroke-width="2"
                      />
                      <!-- Progress circle -->
                      <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none"
                        :stroke="getScoreColor(getAvgScoreNumber(interview.evaluations))"
                        stroke-width="2"
                        :stroke-dasharray="`${getAvgScoreNumber(interview.evaluations) * 10}, 100`"
                        stroke-linecap="round"
                      />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                      <span
                        class="text-xs font-bold"
                        :style="{ color: getScoreColor(getAvgScoreNumber(interview.evaluations)) }"
                      >
                        {{ Math.round(getAvgScoreNumber(interview.evaluations)) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Interview Details -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div class="space-y-4">
                <div class="rounded-lg border p-4">
                  <div class="mb-2 flex items-center gap-2">
                    <CalendarIcon class="h-4 w-4 text-gray-500" />
                    <span class="text-sm font-medium text-gray-600">
                      {{ t('interview.viewInterviewDialog.date') }}
                    </span>
                  </div>
                  <p class="font-medium text-gray-900">
                    {{
                      new Date(interview.date).toLocaleString('fr-FR', {
                        dateStyle: 'full',
                        timeStyle: 'short',
                      })
                    }}
                  </p>
                </div>
              </div>

              <div class="space-y-4">
                <div class="rounded-lg border p-4">
                  <div class="mb-2 flex items-center gap-2">
                    <FileTextIcon class="h-4 w-4 text-gray-500" />
                    <span class="text-sm font-medium text-gray-600">
                      {{ t('interview.viewInterviewDialog.template') }}
                    </span>
                  </div>
                  <p class="font-medium text-gray-900">{{ interview?.template.title }}</p>
                </div>
              </div>
            </div>
          </TabsContent>

          <TabsContent
            v-if="interview?.status == 'completed'"
            value="evaluations"
            class="space-y-4"
          >
            <div class="space-y-6">
              <div
                v-for="group in groupedEvaluations"
                :key="group.question_id"
                class="rounded-lg border bg-white p-5 transition-shadow hover:shadow-sm"
              >
                <!-- Question Header with Average Score -->
                <div class="mb-4 flex items-start justify-between">
                  <h5 class="flex-1 pr-4 font-medium text-gray-900">{{ group.question_title }}</h5>
                  <div class="flex items-center gap-3">
                    <!-- Average Score circle -->
                    <div class="relative h-12 w-12">
                      <svg class="h-12 w-12 -rotate-90 transform" viewBox="0 0 36 36">
                        <!-- Background circle -->
                        <path
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#e5e7eb"
                          stroke-width="3"
                        />
                        <!-- Progress circle -->
                        <path
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          :stroke="getScoreColor(group.average_score)"
                          stroke-width="3"
                          :stroke-dasharray="`${group.average_score * 10}, 100`"
                          stroke-linecap="round"
                        />
                      </svg>
                      <div class="absolute inset-0 flex items-center justify-center">
                        <span
                          class="text-sm font-bold"
                          :style="{ color: getScoreColor(group.average_score) }"
                        >
                          {{ group.average_score.toFixed(1) }}
                        </span>
                      </div>
                    </div>
                    <!-- Average Score badge -->
                    <Badge
                      variant="outline"
                      class="border-2 font-semibold"
                      :style="{
                        color: getScoreColor(group.average_score),
                        borderColor: getScoreColor(group.average_score),
                        backgroundColor: getScoreBackgroundColor(group.average_score),
                      }"
                    >
                      Avg: {{ group.average_score.toFixed(1) }}/10
                    </Badge>
                  </div>
                </div>

                <!-- Average Score bar -->
                <div class="mb-4">
                  <div class="h-2 w-full rounded-full bg-gray-200">
                    <div
                      class="h-2 rounded-full transition-all duration-300"
                      :style="{
                        width: `${(group.average_score / 10) * 100}%`,
                        backgroundColor: getScoreColor(group.average_score),
                      }"
                    ></div>
                  </div>
                </div>

                <!-- Individual Evaluations -->
                <div class="space-y-3">
                  <h6 class="text-sm font-medium text-gray-600">Individual Evaluations:</h6>
                  <div
                    v-for="(evaluation, evalIdx) in group.evaluations"
                    :key="evalIdx"
                    class="rounded-md border-l-4 bg-gray-50 p-3"
                    :style="{ borderLeftColor: getScoreColor(evaluation.score) }"
                  >
                    <div class="mb-2 flex items-center justify-between">
                      <span class="font-medium text-gray-800">{{ evaluation.evaluator.name }}</span>
                      <div class="flex items-center gap-2">
                        <span
                          class="text-sm font-bold"
                          :style="{ color: getScoreColor(evaluation.score) }"
                        >
                          {{ evaluation.score }}/10
                        </span>
                        <div class="relative h-6 w-6">
                          <svg class="h-6 w-6 -rotate-90 transform" viewBox="0 0 36 36">
                            <path
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="#e5e7eb"
                              stroke-width="4"
                            />
                            <path
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              :stroke="getScoreColor(evaluation.score)"
                              stroke-width="4"
                              :stroke-dasharray="`${evaluation.score * 10}, 100`"
                              stroke-linecap="round"
                            />
                          </svg>
                        </div>
                      </div>
                    </div>
                    <div v-if="evaluation.remarks" class="text-sm text-gray-600">
                      {{ evaluation.remarks }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </TabsContent>
        </Tabs>
      </div>
    </DialogContent>
  </Dialog>
</template>
