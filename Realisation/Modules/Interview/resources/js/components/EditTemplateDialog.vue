<script setup lang="ts">
// Import UI components: Button for actions, Dialog components for modal structure
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
// Input and Label for form fields
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Select
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
// Toast utilities for feedback notifications
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
// Inertia router for HTTP PUT requests
import { router } from '@inertiajs/vue3';
// Vee-validate schema integration with Zod
import { toTypedSchema } from '@vee-validate/zod';
// Icons for dynamic type fields
import { Plus, Trash } from 'lucide-vue-next';
// Vee-validate useForm hook for form state and validation
import { useForm } from 'vee-validate';
// Vue reactivity: computed for reactive arrays, watch for prop updates, Ref type
import { computed, Ref, watch } from 'vue';
// i18n composable for translations
import { useI18n } from 'vue-i18n';
// Zod library for schema definitions
import { z } from 'zod';

// Setup translation function for localized text
const { t } = useI18n();

// --- PROPS AND EMITS ---
// Props: whether dialog is open, list of all types, and currently editing template
const props = defineProps<{
  open: boolean;
  types: { id: number; title: string }[];
  template: { id: number; title: string; types: { id: number; title: string }[] } | null;
}>();
// Emit events: to update dialog open state and signal save completion
const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
  (e: 'saved'): void;
}>();

// Setup toast notification hook
const { toast } = useToast();

// --- VALIDATION SCHEMA ---
// Define Zod schema for template edit form
const templateSchema = toTypedSchema(
  z.object({
    title: z
      .string()
      .min(1, t('template.editTemplateDialog.templateSchema.title.min'))
      .max(20, t('template.editTemplateDialog.templateSchema.title.max')),
    types: z.array(z.number().min(1, t('template.editTemplateDialog.templateSchema.types.min'))),
  }),
);

// --- FORM SETUP ---
// Initialize form with validation schema and default values
const { defineField, handleSubmit, errors, resetForm, setValues } = useForm({
  validationSchema: templateSchema,
  initialValues: { title: '', types: [0] },
});
// Define reactive fields for title and types array
const [title, titleAttrs] = defineField('title');
const [formTypes] = defineField('types') as unknown as [Ref<number[]>, any];

// --- PROP WATCHER ---
// Watch props.open and props.template to populate or reset form
watch(
  () => [props.open, props.template],
  ([open, template]) => {
    if (open && template) {
      // Populate form values from the selected template
      setValues({
        title: template.title,
        types: template.types.map((b) => b.id),
      });
    } else if (!open) {
      // Reset form when dialog closes
      resetForm();
    }
  },
);

// --- COMPUTED HELPERS ---
// Determine which types are available for selection at each index
const availableTypes = computed(() => (index: number) => {
  const used = new Set(formTypes.value.filter((_, i) => i !== index));
  return props.types.filter((b) => !used.has(b.id) || b.id === formTypes.value[index]);
});
// Allow adding more type selects if not all types are chosen
const canAddMore = computed(() => formTypes.value.length < props.types.length);

// --- DYNAMIC TYPE HANDLERS ---
// Add another type select field
const addType = () => formTypes.value.push(0);
// Remove a type select field, ensuring at least one remains
const removeType = (i: number) => {
  if (formTypes.value.length > 1) {
    formTypes.value.splice(i, 1);
  }
};

// --- FORM SUBMISSION ---
// Handle template edit submission
const editTemplate = handleSubmit(async (values) => {
  if (!props.template) return;
  // Send PUT request to update template
  await router.put(`/dashboard/template/${props.template.id}`, values, {
    onSuccess: () => {
      // Show success toast, close dialog, and emit saved event
      toast({
        title: t('template.editTemplateDialog.toast.success.title'),
        description: t('template.editTemplateDialog.toast.success.description'),
      });
      emit('update:open', false);
      emit('saved');
    },
    onError: (errs) => {
      // Show error toast and log errors
      toast({
        title: t('template.editTemplateDialog.toast.error.title'),
        description: t('template.editTemplateDialog.toast.error.description'),
      });
      console.error(errs);
    },
  });
});
</script>

<template>
  <!-- Dialog wrapper bound to open prop -->
  <Dialog :open="props.open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[600px] overflow-y-auto">
      <DialogHeader>
        <!-- Dialog title and description -->
        <DialogTitle>{{ t('template.editTemplateDialog.title') }}</DialogTitle>
        <DialogDescription>{{ t('template.editTemplateDialog.description') }}</DialogDescription>
      </DialogHeader>

      <!-- Only show form if template data is present -->
      <template v-if="props.template">
        <form @submit.prevent="editTemplate" class="flex flex-col gap-6">
          <!-- Title input -->
          <div class="flex flex-col gap-2">
            <Label for="title">{{ t('template.editTemplateDialog.templateTitle') }}</Label>
            <Input id="title" v-model="title" v-bind="titleAttrs" />
            <p v-if="errors.title" class="text-sm text-red-500">{{ errors.title }}</p>
          </div>

          <!-- Dynamic type selection -->
          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
              <Label>{{ t('template.editTemplateDialog.types') }}</Label>
              <Button v-if="canAddMore" type="button" @click="addType"><Plus /></Button>
            </div>
            <div v-for="(sel, index) in formTypes" :key="index" class="flex items-center gap-2">
              <Select v-model="formTypes[index]" class="w-full rounded border px-3 py-2">
                <SelectTrigger>
                  <SelectValue
                    :placeholder="t('template.editTemplateDialog.typeSelectPlaceholder')"
                  />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>
                      {{ t('template.editTemplateDialog.typeSelectLabel') }}
                    </SelectLabel>
                    <SelectItem v-for="opt in availableTypes(index)" :key="opt.id" :value="opt.id">
                      {{ opt.title }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
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
          <Button type="submit" class="mt-4 w-full">{{
            t('template.editTemplateDialog.edit')
          }}</Button>
        </form>
      </template>
    </DialogContent>
  </Dialog>

  <!-- Toast container for notifications -->
  <Toaster />
</template>
