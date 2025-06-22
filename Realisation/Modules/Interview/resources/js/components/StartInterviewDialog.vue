<script setup lang="ts">
// Import UI components for dialogs
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

// Add new imports for Button components
import { Button } from '@/components/ui/button';

// Toast utilities for notifications
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';

// Add new imports for form components
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';

// Icons for adding/removing type fields
import { ArrowLeft, Check } from 'lucide-vue-next';

// Vue utilities: computed for reactive arrays, Ref type
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n'; // Import useI18n

// Internationalization for text translations
const { t } = useI18n();

// Add new state for the current user ID
const page = usePage();

// Toast hook for showing notifications
const { toast } = useToast();

// Props: open state and available types list
const props = defineProps<{
  open: boolean;
  interviews: {
    id: string;
    status: string;
    date: string;
    created_at: string;
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
  }[];
}>();

// Emit event to update `open` prop externally
const emit = defineEmits(['update:open', 'submit']);

// Add new state for the searchable select
const selectedCandidateId = ref('all');
const currentStep = ref<'candidate' | 'questions'>('candidate');

// State for question responses
const questionScores = ref<Record<number, number>>({});
const questionRemarks = ref<Record<number, string>>({});

const uniqueCandidates = computed(() => {
  const map = new Map<number, { id: number; name: string }>();
  props.interviews.forEach((i) => {
    const cand = i.candidate;
    if (!map.has(cand.id)) {
      map.set(cand.id, cand);
    }
  });
  return Array.from(map.values()).sort((a, b) => a.name.localeCompare(b.name));
});

function handleCandidateChange() {
  if (selectedCandidateId.value !== 'all') {
    currentStep.value = 'questions';
  }
}

const candidateOptions = computed(() => [
  { id: 'all', name: t('interview.startInterviewDialog.allCandidates') },
  ...uniqueCandidates.value,
]);

const selectedCandidateName = computed(() => {
  const selected = candidateOptions.value.find((c) => c.id === selectedCandidateId.value);
  return selected ? selected.name : t('interview.startInterviewDialog.selectCandidate');
});

// Get the selected candidate's interview and template
const selectedInterview = computed(() => {
  if (selectedCandidateId.value === 'all') return null;
  return props.interviews.find((i) => i.candidate.id === selectedCandidateId.value);
});

const questionsByType = computed(() => {
  if (!selectedInterview.value) return [];

  return selectedInterview.value.template.types.map((type) => ({
    ...type,
    questions: type.questions,
  }));
});

function setQuestionScore(questionId: number, score: number) {
  questionScores.value[questionId] = score;
}

function goBack() {
  currentStep.value = 'candidate';
  selectedCandidateId.value = 'all';
}

function submitEvaluation() {
  const userId = computed(() => page.props.auth.user?.id || null);

  const evaluations = Object.keys(questionScores.value).map((questionId) => ({
    evaluatorId: userId.value,
    interviewId: selectedInterview.value?.id,
    questionId: parseInt(questionId),
    score: questionScores.value[parseInt(questionId)],
    remarks: questionRemarks.value[parseInt(questionId)] || '',
  }));

  router.post('/dashboard/evaluation', evaluations, {
    onSuccess: () => {
      // Close dialog, show success toast, reset form
      emit('update:open', false);
      toast({
        title: t('interview.startInterviewDialog.toast.success.title'),
        description: t('interview.startInterviewDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      // Show error toast and log errors
      toast({
        title: t('interview.startInterviewDialog.toast.error.title'),
        description: t('interview.startInterviewDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });

  // Reset form
  questionScores.value = {};
  questionRemarks.value = {};
  currentStep.value = 'candidate';
  selectedCandidateId.value = 'all';
}

// Reset form when dialog closes
watch(
  () => props.open,
  (newOpen) => {
    if (!newOpen) {
      currentStep.value = 'candidate';
      selectedCandidateId.value = 'all';
      questionScores.value = {};
      questionRemarks.value = {};
    }
  },
);
</script>

<template>
  <!-- Dialog bound to `open` prop -->
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent
      class="max-h-[700px] max-w-md overflow-y-auto"
      :class="{ 'max-w-2xl': currentStep === 'questions' }"
    >
      <DialogHeader>
        <!-- Title and description for starting an interview -->
        <DialogTitle>
          <div class="flex items-center gap-2">
            <Button
              v-if="currentStep === 'questions'"
              variant="ghost"
              size="sm"
              @click="goBack"
              class="p-1"
            >
              <ArrowLeft class="h-4 w-4" />
            </Button>
            {{
              currentStep === 'candidate'
                ? t('interview.startInterviewDialog.title1')
                : t('interview.startInterviewDialog.title2') + selectedCandidateName
            }}
          </div>
        </DialogTitle>
        <DialogDescription>
          {{
            currentStep === 'candidate'
              ? t('interview.startInterviewDialog.description1')
              : t('interview.startInterviewDialog.description2')
          }}
        </DialogDescription>
      </DialogHeader>

      <!-- Candidate Selection Step -->
      <div v-if="currentStep === 'candidate'">
        <!-- Replace the Popover section with this simple select -->
        <div class="space-y-2">
          <Label for="candidate-select">
            {{ t('interview.startInterviewDialog.selectCandidate') }}
          </Label>
          <select
            id="candidate-select"
            v-model="selectedCandidateId"
            @change="handleCandidateChange"
            class="w-full rounded-md border border-input bg-background px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-ring"
          >
            <option value="all">{{ t('interview.startInterviewDialog.allCandidates') }}</option>
            <option v-for="candidate in uniqueCandidates" :key="candidate.id" :value="candidate.id">
              {{ candidate.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- Questions Step -->
      <div v-if="currentStep === 'questions' && selectedInterview" class="space-y-6">
        <div class="space-y-4">
          <Card v-for="type in questionsByType" :key="type.id">
            <CardHeader>
              <CardTitle class="text-lg font-semibold text-primary">
                {{ t('interview.startInterviewDialog.typeTitle') + type.title }}
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
              <div v-for="question in type.questions" :key="question.id" class="space-y-4">
                <div>
                  <Label class="text-base font-medium">
                    {{ t('interview.startInterviewDialog.questionTitle') + question.title }}
                  </Label>
                  <div class="mt-2 space-y-3">
                    <!-- Score Input with 10 squares -->
                    <div>
                      <div class="flex gap-1">
                        <button
                          v-for="score in 10"
                          :key="score"
                          @click="setQuestionScore(question.id, score)"
                          class="h-8 w-8 rounded transition-all duration-200 hover:scale-105"
                          :class="[
                            questionScores[question.id] === score
                              ? 'scale-110 shadow-lg'
                              : 'border-gray-300',
                            // Always show gradient colors from red to green
                            score <= 2
                              ? 'bg-[#ef4444]'
                              : score <= 4
                                ? 'bg-[#f97316]'
                                : score <= 5
                                  ? 'bg-[#f59e0b]'
                                  : score <= 6
                                    ? 'bg-[#eab308]'
                                    : score <= 7
                                      ? 'bg-[#84cc16]'
                                      : score <= 8
                                        ? 'bg-[#84cc16]'
                                        : 'bg-[#8de20d]',
                          ]"
                        >
                          <span class="text-xs font-bold text-white drop-shadow-sm">
                            {{ score }}
                          </span>
                        </button>
                      </div>
                      <div
                        v-if="questionScores[question.id]"
                        class="mt-1 text-sm text-muted-foreground"
                      >
                        {{
                          t('interview.startInterviewDialog.selected') +
                          questionScores[question.id]
                        }}/10
                      </div>
                    </div>

                    <!-- Remarks Input -->
                    <div>
                      <Label class="mb-2 block text-sm text-muted-foreground">{{
                        t('interview.startInterviewDialog.remarks')
                      }}</Label>
                      <Textarea
                        v-model="questionRemarks[question.id]"
                        class="min-h-[80px] resize-none"
                      />
                    </div>
                  </div>
                </div>
                <Separator v-if="question !== type.questions[type.questions.length - 1]" />
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end border-t pt-4">
          <Button @click="submitEvaluation" class="flex items-center gap-2">
            <Check class="h-4 w-4" />
            {{ t('interview.startInterviewDialog.submit') }}
          </Button>
        </div>
      </div>
    </DialogContent>
  </Dialog>
  <!-- Toast container for showing notifications -->
  <Toaster />
</template>

<style scoped>
.bg-gradient-score {
  background: linear-gradient(90deg, #ef4444 0%, #f59e0b 30%, #eab308 60%, #22c55e 100%);
}
</style>
