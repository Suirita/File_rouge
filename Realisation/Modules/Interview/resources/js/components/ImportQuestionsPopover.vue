<script setup lang="ts">
// Import UI components: Button for actions, Input for file selection, Label for field label
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Popover for import dialog, with tooltip for trigger
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
// Toast utilities for user feedback
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
// Inertia router for handling form submission
import { router } from '@inertiajs/vue3';
// Vee-validate integration with Zod schema for file validation
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
// Zod for schema definitions
import * as z from 'zod';
// Icon for import button
import { FileDown } from 'lucide-vue-next';
// i18n for translations
import { useI18n } from 'vue-i18n';

// Setup translation helper
const { t } = useI18n();
// Setup toast notification hook
const { toast } = useToast();

// --- VALIDATION SCHEMA ---
// Define schema: expecting a File instance under key 'file'
const typeSchema = toTypedSchema(
  z.object({
    file: z.instanceof(File),
  }),
);

// --- FORM SETUP ---
// Initialize form handling with schema and default values
const { handleSubmit, errors, resetForm, setFieldValue } = useForm({
  validationSchema: typeSchema,
  initialValues: { file: undefined },
});

// --- FILE INPUT HANDLER ---
// Listen for file selection and update form field
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    setFieldValue('file', target.files[0]);
  }
};

// --- IMPORT SUBMISSION ---
// Submit selected file to import endpoint via POST, using FormData
const importTypees = handleSubmit(async (values) => {
  router.post('/dashboard/question/import', values, {
    forceFormData: true, // ensures file sent as FormData
    onSuccess: () => {
      // Show success toast and reset form
      toast({
        title: t('question.importQuestionsPopover.toast.success.title'),
        description: t('question.importQuestionsPopover.toast.success.description'),
      });
      resetForm();
    },
    onError: (errors) => {
      // Show error toast and log errors
      toast({
        title: t('question.importQuestionsPopover.toast.error.title'),
        description: t('question.importQuestionsPopover.toast.error.description'),
      });
      console.error(errors);
    },
  });
});
</script>

<template>
  <!-- Popover wrap for import UI -->
  <Popover>
    <PopoverTrigger>
      <!-- Tooltip-wrapped button to trigger import popover -->
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger>
            <Button size="sm" variant="outline" class="h-8 gap-1">
              <FileDown class="h-3.5 w-3.5" />
              <!-- Download icon -->
            </Button>
          </TooltipTrigger>
          <TooltipContent>
            <p>{{ t('question.import') }}</p>
          </TooltipContent>
        </Tooltip>
      </TooltipProvider>
    </PopoverTrigger>
    <!-- Popover content containing file upload form -->
    <PopoverContent class="w-96 rounded-md border p-4 shadow-lg">
      <header class="mb-4">
        <h1 class="text-lg font-bold">{{ t('question.importQuestionsPopover.title') }}</h1>
        <p class="text-sm text-gray-600">{{ t('question.importQuestionsPopover.description') }}</p>
      </header>
      <form @submit.prevent="importTypees" class="flex flex-col gap-4">
        <!-- File input field -->
        <div class="flex flex-col gap-2">
          <Label for="file">{{ t('question.importQuestionsPopover.file') }}</Label>
          <Input id="file" type="file" @change="handleFileChange" />
          <p v-if="errors.file" class="text-sm text-red-500">{{ errors.file }}</p>
        </div>
        <!-- Submit button -->
        <Button type="submit" class="w-full">{{ t('question.importQuestionsPopover.import') }}</Button>
      </form>
    </PopoverContent>
  </Popover>
  <!-- Toast container for notifications -->
  <Toaster />
</template>
