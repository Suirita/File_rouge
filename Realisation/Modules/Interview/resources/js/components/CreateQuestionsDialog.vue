<script setup lang="ts">
// Import UI button component
import { Button } from '@/components/ui/button';
// Import Dialog components for modal structure
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
// Import form input and label components
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Import Toaster and toast hook for notifications
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
// Inertia router for form submission
import { router } from '@inertiajs/vue3';
// Vee-validate schema from Zod for form validation
import { toTypedSchema } from '@vee-validate/zod';
// Icons for add/remove actions
import { Plus, Trash } from 'lucide-vue-next';
// Vee-validate useForm composable for handling form state
import { useForm } from 'vee-validate';
// Vue Ref type and i18n for translations
import { Ref } from 'vue';
import { useI18n } from 'vue-i18n';
// Zod for defining validation schema
import { z } from 'zod';

// Setup translation function
const { t } = useI18n();

// Define prop: controls whether dialog is open
defineProps<{ open: boolean }>();

// Setup toast notification
const { toast } = useToast();
// Emit event to update open state externally
const emit = defineEmits(['update:open']);

// --- VALIDATION SCHEMA ---
// Define Zod schema for type creation form
const questionSchema = toTypedSchema(
  z.object({
    type: z
      .string()
      .min(1, t('question.createQuestionsDialog.questionSchema.type.min'))
      .max(20, t('question.createQuestionsDialog.questionSchema.type.max')),
    questions: z.array(
      z
        .string()
        .min(1, t('question.createQuestionsDialog.questionSchema.questions.min'))
        .max(50, t('question.createQuestionsDialog.questionSchema.questions.max')),
    ),
  }),
);

// --- FORM SETUP ---
// Initialize form with validation schema and default values
const { defineField, handleSubmit, errors, resetForm } = useForm({
  validationSchema: questionSchema,
  initialValues: {
    type: '',
    questions: [''],
  },
});

// Define reactive fields for type and questions
const [type, typeAttrs] = defineField('type');
// Cast questions field to typed array
const [questions] = defineField('questions') as unknown as [Ref<string[]>, unknown];

// --- FORM SUBMISSION ---
// Handle type creation on form submit
const addType = handleSubmit(async (values) => {
  // Post form data to server
  router.post('/dashboard/question', values, {
    onSuccess: () => {
      // Close dialog and show success toast
      emit('update:open', false);
      toast({
        title: t('question.createQuestionsDialog.toast.success.title'),
        description: t('question.createQuestionsDialog.toast.success.description'),
      });
      // Reset form fields
      resetForm();
    },
    onError: (errors) => {
      // Show error toast and log errors
      toast({
        title: t('question.createQuestionsDialog.toast.error.title'),
        description: t('question.createQuestionsDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
});

// --- QUESTIONS HANDLERS ---
// Add new empty question field
const addQuestion = () => {
  questions.value = [...(questions.value ?? []), ''];
};
// Remove question at given index if more than one question exists
const removeQuestion = (index: number) => {
  if (questions.value && questions.value.length > 1) {
    questions.value.splice(index, 1);
  }
};
</script>

<template>
  <!-- Dialog wrapper bound to open prop -->
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[600px] overflow-y-auto">
      <DialogHeader>
        <!-- Dialog title and description -->
        <DialogTitle>{{ t('question.createQuestionsDialog.title') }}</DialogTitle>
      </DialogHeader>
      <!-- Type creation form -->
      <form @submit.prevent="addType" class="flex flex-col gap-6">
        <!-- Type input field -->
        <div class="flex flex-col gap-2">
          <Label for="type">{{ t('question.createQuestionsDialog.type') }}</Label>
          <Input id="type" v-model="type" v-bind="typeAttrs" />
          <p v-if="errors.type" class="text-sm text-red-500">{{ errors.type }}</p>
        </div>
        <!-- Questions dynamic fields -->
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <Label>{{ t('question.createQuestionsDialog.questions') }}</Label>
            <!-- Button to add another question -->
            <Button type="button" @click="addQuestion"><Plus /></Button>
          </div>
          <!-- Loop through questions array -->
          <div v-for="(question, index) in questions" :key="index" class="flex items-center gap-2">
            <Input v-model="questions[index]" />
            <!-- Show remove button only for additional fields -->
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
        <Button type="submit" class="mt-4 w-full">{{ t('question.createQuestionsDialog.add') }}</Button>
      </form>
    </DialogContent>
  </Dialog>
  <!-- Toast container for notifications -->
  <Toaster />
</template>
