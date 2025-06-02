<script setup lang="ts">
// Import UI components: Button for actions, Dialog for modal container
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
// Form input and label components
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Toast utilities for feedback messages
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
// Inertia router for HTTP requests
import { router } from '@inertiajs/vue3';
// Vee-validate integration with Zod schema
import { toTypedSchema } from '@vee-validate/zod';
// Icons for dynamic question fields
import { Plus, Trash } from 'lucide-vue-next';
// Vee-validate composable for form state and validation
import { useForm } from 'vee-validate';
// Vue reactivity utilities and i18n
import { computed, ref, watch, watchEffect } from 'vue';
import { useI18n } from 'vue-i18n';
// Zod for schema definition
import { z } from 'zod';

// Setup translation function
const { t } = useI18n();

// Component props: open state and optional type object to edit
const props = defineProps<{
  open: boolean;
  type?: { id: number; title: string; questions: { id: number; title: string }[] };
}>();

// Toast hook for showing notifications
const { toast } = useToast();
// Emit function to update the open state externally
const emit = defineEmits(['update:open']);

// --- VALIDATION SCHEMA ---
// Define Zod-based form schema for editing a type
const questionSchema = toTypedSchema(
  z.object({
    type: z
      .string()
      .min(1, t('question.editQuestionsDialog.questionSchema.type.min'))
      .max(20, t('question.editQuestionsDialog.questionSchema.type.max')),
    questions: z.array(
      z
        .string()
        .min(1, t('question.editQuestionsDialog.questionSchema.questions.min'))
        .max(50, t('question.editQuestionsDialog.questionSchema.questions.max')),
    ),
  }),
);

// --- FORM SETUP ---
// Initialize form with validation schema and default empty values
const { handleSubmit, errors, resetForm, setValues, values, setFieldValue } = useForm({
  validationSchema: questionSchema,
  initialValues: { type: '', questions: [''] },
});

// Local reactive array to manage dynamic question inputs separately
const localQuestions = ref<string[]>([]);

// Populate form when props.type changes
watchEffect(() => {
  if (props.type) {
    // Map type.questions to an array of titles, or default to one empty string
    const qs = props.type.questions.length ? props.type.questions.map((q) => q.title) : [''];
    // Set form fields to type values
    setValues({ type: props.type.title, questions: qs });
    // Sync localQuestions for template rendering
    localQuestions.value = qs;
  }
});

// Watch localQuestions and update form's questions field
watch(
  localQuestions,
  (newVal) => {
    setFieldValue('questions', newVal);
  },
  { deep: true },
);

// Computed model to bind title type with form values
const typeModel = computed({
  get: () => values.type,
  set: (val: string) => setFieldValue('type', val),
});

// --- FORM SUBMISSION ---
// Handle type update on form submit
const editType = handleSubmit(async (formValues) => {
  if (!props.type) return;
  // Ensure formValues.questions reflect localQuestions
  formValues.questions = localQuestions.value;
  // Submit PUT request to update type
  router.put(`/dashboard/question/${props.type.id}`, formValues, {
    onSuccess: () => {
      // Close dialog, show success toast, reset form state
      emit('update:open', false);
      toast({
        title: t('question.editQuestionsDialog.toast.success.title'),
        description: t('question.editQuestionsDialog.toast.success.description'),
      });
      resetForm();
      localQuestions.value = [''];
    },
    onError: (err) => {
      // Show error toast and log error details
      toast({
        title: t('question.editQuestionsDialog.toast.error.title'),
        description: t('question.editQuestionsDialog.toast.error.description'),
      });
      console.error(err);
    },
  });
});

// --- DYNAMIC QUESTION HANDLERS ---
// Add an additional question field
const addQuestion = () => {
  localQuestions.value.push('');
};
// Remove a question field, ensuring at least one remains
const removeQuestion = (index: number) => {
  if (localQuestions.value.length > 1) {
    localQuestions.value.splice(index, 1);
  }
};
</script>

<template>
  <!-- Dialog modal bound to open prop -->
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[600px] overflow-y-auto">
      <DialogHeader>
        <!-- Dialog title showing type title -->
        <DialogTitle
          >{{ t('question.editQuestionsDialog.title') }}: {{ props.type.title }}</DialogTitle
        >
      </DialogHeader>
      <!-- Edit form -->
      <form @submit.prevent="editType" class="flex flex-col gap-6">
        <!-- Type title input -->
        <div class="flex flex-col gap-2">
          <Label for="type">{{ t('question.editQuestionsDialog.type') }}</Label>
          <Input id="type" v-model="typeModel" />
          <p v-if="errors.type" class="text-sm text-red-500">{{ errors.type }}</p>
        </div>

        <!-- Dynamic questions fields -->
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <Label>{{ t('question.editQuestionsDialog.questions') }}</Label>
            <!-- Button to add question -->
            <Button type="button" @click="addQuestion"><Plus /></Button>
          </div>
          <!-- Render each question input -->
          <div
            v-for="(question, index) in localQuestions"
            :key="index"
            class="flex items-center gap-2"
          >
            <Input v-model="localQuestions[index]" />
            <!-- Remove button for additional fields -->
            <Button
              v-if="index > 0"
              type="button"
              @click="removeQuestion(index)"
              class="bg-red-500 hover:bg-red-400"
            >
              <Trash />
            </Button>
          </div>
          <p v-if="errors.questions" class="text-sm text-red-500">{{ errors.questions }}</p>
        </div>

        <!-- Submit button -->
        <Button type="submit" class="mt-4 w-full">{{
          t('question.editQuestionsDialog.edit')
        }}</Button>
      </form>
    </DialogContent>
  </Dialog>
  <!-- Toast container for showing notifications -->
  <Toaster />
</template>
