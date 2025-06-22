<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { watch, type Ref } from 'vue';
import { useI18n } from 'vue-i18n';

import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';

import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { z } from 'zod';

const { t } = useI18n();
const { toast } = useToast();

// Props & emits
const props = defineProps<{
  open: boolean;
  interview?: {
    id: string;
    status: string;
    date: string; // ISO full string or "YYYY-MM-DD"
    candidate: { id: number; name: string };
    template?: { id: number; title: string };
  };
  templates: { id: number; title: string }[];
}>();
const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
  (e: 'saved'): void;
}>();

// Validation schema
const interviewSchema = toTypedSchema(
  z.object({
    status: z.string().min(1, t('interview.editInterviewDialog.interviewSchema.status')),
    template: z.number().min(1, t('interview.editInterviewDialog.interviewSchema.template')),
    date: z.string().min(1, t('interview.editInterviewDialog.interviewSchema.date')),
  }),
);

// Vee-Validate form
const { defineField, handleSubmit, resetForm, setValues, errors } = useForm({
  validationSchema: interviewSchema,
  initialValues: { status: '', template: 0, date: '' },
});

const [formStatus] = defineField('status') as [Ref<string>, unknown];
const [formTemplate] = defineField('template') as [Ref<number>, unknown];
const [formDate] = defineField('date') as [Ref<string>, unknown];

// Watch open/close to reset form
watch(
  () => props.open,
  (open) => {
    if (!open) resetForm();
  },
);

// Populate when interview changes
watch(
  () => props.interview,
  (iv) => {
    if (iv) {
      // take first 10 chars of ISO to get YYYY-MM-DD
      const isoDate = iv.date.slice(0, 10);
      setValues({
        status: iv.status,
        template: iv.template?.id ?? 0,
        date: isoDate,
      });
    }
  },
  { immediate: true },
);

// Status dropdown options
const statusOptions = ['pending', 'absent'];

// Submit handler
const editInterview = handleSubmit(async (values) => {
  if (!props.interview) return;
  await router.put(`/dashboard/interview/${props.interview.id}`, values, {
    onSuccess: () => {
      toast({
        title: t('interview.editInterviewDialog.toast.success.title'),
        description: t('interview.editInterviewDialog.toast.success.description'),
      });
      emit('update:open', false);
      emit('saved');
    },
    onError: () => {
      toast({
        title: t('interview.editInterviewDialog.toast.error.title'),
        description: t('interview.editInterviewDialog.toast.error.description'),
      });
    },
  });
});
</script>

<template>
  <Dialog :open="props.open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-h-[600px] overflow-y-auto">
      <DialogHeader>
        <DialogTitle>{{ t('interview.editInterviewDialog.title') }}</DialogTitle>
        <DialogDescription>{{ t('interview.editInterviewDialog.description') }}</DialogDescription>
      </DialogHeader>

      <form v-if="props.interview" @submit.prevent="editInterview" class="flex flex-col gap-6">
        <!-- Status -->
        <div class="flex flex-col gap-2">
          <Label>{{ t('interview.editInterviewDialog.status') }}</Label>
          <Select v-model="formStatus" class="w-full">
            <SelectTrigger>
              <SelectValue placeholder="Select status" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>{{
                  t('interview.editInterviewDialog.statusSelectLabel')
                }}</SelectLabel>
                <SelectItem v-for="opt in statusOptions" :key="opt" :value="opt">
                  {{ t(`interview.statusOptions.${opt}`) }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <span v-if="errors.status" class="text-sm text-red-500">{{ errors.status }}</span>
        </div>

        <!-- Template -->
        <div class="flex flex-col gap-2">
          <Label>{{ t('interview.editInterviewDialog.template') }}</Label>
          <Select v-model="formTemplate" class="w-full">
            <SelectTrigger>
              <SelectValue placeholder="Select template" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel>{{
                  t('interview.editInterviewDialog.templateSelectLabel')
                }}</SelectLabel>
                <SelectItem v-for="tmpl in props.templates" :key="tmpl.id" :value="tmpl.id">{{
                  tmpl.title
                }}</SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <span v-if="errors.template" class="text-sm text-red-500">{{ errors.template }}</span>
        </div>

        <!-- Date (native) -->
        <div class="flex flex-col gap-2">
          <Label>{{ t('interview.editInterviewDialog.date') }}</Label>
          <input type="date" v-model="formDate" class="w-full rounded border px-3 py-2" />
          <span v-if="errors.date" class="text-sm text-red-500">{{ errors.date }}</span>
        </div>

        <!-- Submit -->
        <Button type="submit" class="mt-4 w-full">
          {{ t('interview.editInterviewDialog.edit') }}
        </Button>
      </form>
    </DialogContent>
  </Dialog>

  <Toaster />
</template>
