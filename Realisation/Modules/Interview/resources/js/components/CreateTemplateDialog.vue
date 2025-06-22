<script setup lang="ts">
// Import UI components for buttons and dialogs
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Import select components for type selection
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
// Toast utilities for notifications
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';

// Inertia router for form submissions and current page context
import { router } from '@inertiajs/vue3';
// Vee-validate schema creation using Zod
import { toTypedSchema } from '@vee-validate/zod';
// Icons for adding/removing type fields
import { Plus, Trash } from 'lucide-vue-next';
// Vee-validate hook for form state and validation
import { useForm } from 'vee-validate';
// Vue utilities: computed for reactive arrays, Ref type
import { computed, Ref } from 'vue';
// Internationalization for text translations
import { useI18n } from 'vue-i18n';
// Zod for defining validation schema
import { z } from 'zod';

// Setup translation function
const { t } = useI18n();

// Props: open state and available types list
const props = defineProps<{ open: boolean; types: { id: number; title: string }[] }>();

// Toast hook for showing notifications
const { toast } = useToast();
// Emit event to update `open` prop externally
const emit = defineEmits(['update:open']);

// --- VALIDATION SCHEMA ---
// Define form schema: title must be alphanumeric/spaces, 1-20 chars
const templateSchema = toTypedSchema(
  z.object({
    title: z
      .string()
      .min(1, t('template.createTemplateDialog.templateSchema.title.min'))
      .max(20, t('template.createTemplateDialog.templateSchema.title.max')),
    // types array is defined by formFields, validated as numbers
    types: z.array(z.number().min(1, t('template.createTemplateDialog.templateSchema.types.min'))),
  }),
);

// --- FORM SETUP ---
// Initialize form with schema and default values (one type selected)
const { defineField, handleSubmit, errors, resetForm } = useForm({
  validationSchema: templateSchema,
  initialValues: { title: '', types: [0] },
});

// Define reactive fields: title and types array
const [title, titleAttrs] = defineField('title');
const [formTypes] = defineField('types') as unknown as [Ref<number[]>, any];

// --- COMPUTED HELPERS ---
// For a given index, filter out types already selected elsewhere
const availableTypes = computed(() => (index: number) => {
  const selectedElsewhere = new Set(formTypes.value.filter((_, i) => i !== index));
  return props.types.filter((b) => !selectedElsewhere.has(b.id) || b.id === formTypes.value[index]);
});
// Allow adding more selects if not all types are used
const canAddMore = computed(() => formTypes.value.length < props.types.length);

// --- FORM SUBMISSION ---
// Submit template creation to backend
const addTemplate = handleSubmit(async (values) => {
  router.post('/dashboard/template', values, {
    onSuccess: () => {
      // Close dialog, show success toast, reset form
      emit('update:open', false);
      toast({
        title: t('template.createTemplateDialog.toast.success.title'),
        description: t('template.createTemplateDialog.toast.success.description'),
      });
      resetForm();
    },
    onError: (errors) => {
      // Show error toast and log errors
      toast({
        title: t('template.createTemplateDialog.toast.error.title'),
        description: t('template.createTemplateDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
});

// --- DYNAMIC TYPE FIELDS ---
// Add another type selection field
const addType = () => {
  formTypes.value = [...formTypes.value, 0];
};
// Remove type field at a given index (keep at least one select)
const removeType = (i: number) => {
  if (formTypes.value.length > 1) {
    formTypes.value.splice(i, 1);
  }
};
</script>

<template>
  <!-- Dialog bound to `open` prop -->
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[600px] overflow-y-auto">
      <DialogHeader>
        <!-- Title and description for creating an template -->
        <DialogTitle>{{ t('template.createTemplateDialog.title') }}</DialogTitle>
        <DialogDescription>{{ t('template.createTemplateDialog.description') }}</DialogDescription>
      </DialogHeader>
      <!-- Template creation form -->
      <form @submit.prevent="addTemplate" class="flex flex-col gap-6">
        <!-- Title input field -->
        <div class="flex flex-col gap-2">
          <Label for="title">{{ t('template.createTemplateDialog.templateTitle') }}</Label>
          <Input id="title" v-model="title" v-bind="titleAttrs" />
          <p v-if="errors.title" class="text-sm text-red-500">{{ errors.title }}</p>
        </div>

        <!-- Dynamic type selectors -->
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <Label>{{ t('template.createTemplateDialog.types') }}</Label>
            <Button v-if="canAddMore" type="button" @click="addType"><Plus /></Button>
          </div>
          <div v-for="(sel, index) in formTypes" :key="index" class="flex items-center gap-2">
            <!-- Type select dropdown -->
            <Select v-model="formTypes[index]" class="w-full rounded border px-3 py-2">
              <SelectTrigger>
                <SelectValue
                  :placeholder="t('template.createTemplateDialog.typeSelectPlaceholder')"
                />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectLabel>
                    {{ t('template.createTemplateDialog.typeSelectLabel') }}
                  </SelectLabel>
                  <SelectItem v-for="opt in availableTypes(index)" :key="opt.id" :value="opt.id">
                    {{ opt.title }}
                  </SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <!-- Remove type button (for additional fields) -->
            <Button
              v-if="index > 0"
              type="button"
              @click="removeType(index)"
              class="bg-red-500 hover:bg-red-400"
            >
              <Trash />
            </Button>
          </div>
          <p v-if="errors.types" class="text-sm text-red-500">{{ errors.types }}</p>
        </div>

        <!-- Submit button -->
        <Button type="submit" class="mt-4 w-full">
          {{ t('template.createTemplateDialog.add') }}
        </Button>
      </form>
    </DialogContent>
  </Dialog>
  <!-- Toast container for showing notifications -->
  <Toaster />
</template>
