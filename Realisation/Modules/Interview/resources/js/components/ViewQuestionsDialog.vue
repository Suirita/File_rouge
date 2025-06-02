<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
  open: boolean;
  type?: { title: string; questions: { id: number; title: string }[] };
}>();

const emit = defineEmits(['update:open']);
</script>

<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-w-lg rounded-lg p-6 shadow-lg">
      <DialogHeader>
        <DialogTitle class="text-xl font-semibold">
          {{ t('question.viewQuestionsDialog.title') }}:
          <span class="text-2xl font-bold text-foreground">{{ type?.title }}</span>
        </DialogTitle>
        <DialogDescription class="mt-6 text-lg"
          >{{ t('question.viewQuestionsDialog.questions') }}:
        </DialogDescription>
      </DialogHeader>
      <ul v-if="type?.questions?.length" class="space-y-2">
        <li v-for="question in type.questions" :key="question.id" class="rounded-md p-3">
          {{ question.title }}
        </li>
      </ul>
      <p v-else class="text-center">{{ t('question.viewQuestionsDialog.noQuestions') }}</p>
    </DialogContent>
  </Dialog>
</template>
