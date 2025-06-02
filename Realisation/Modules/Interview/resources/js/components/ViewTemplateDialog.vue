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
  template?: { title: string; types: { id: string; title: string }[] };
}>();

const emit = defineEmits(['update:open']);
</script>

<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent class="max-w-lg rounded-lg p-6 shadow-lg">
      <DialogHeader>
        <DialogTitle class="text-xl font-semibold">
          {{ t('template.viewTemplateDialog.title') }}:
          <span class="text-2xl font-bold">{{ template?.title }}</span>
        </DialogTitle>
        <DialogDescription class="text- mt-6"
          >{{ t('template.viewTemplateDialog.types') }}:
        </DialogDescription>
      </DialogHeader>
      <ul v-if="template?.types?.length" class="space-y-2">
        <li v-for="branch in template.types" :key="branch.id" class="rounded-md p-3">
          {{ branch.title }}
        </li>
      </ul>
      <p v-else class="text-center">{{ t('template.viewTemplateDialog.noTypes') }}</p>
    </DialogContent>
  </Dialog>
</template>
