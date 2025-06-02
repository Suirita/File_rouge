<script setup lang="ts">
// Import UI components: Button for action, Dialog for modal structure
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
// Toaster and toast hook for feedback notifications
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
// Inertia router for delete request
import { router } from '@inertiajs/vue3';
// i18n for translated text
import { useI18n } from 'vue-i18n';

// Setup translation helper
const { t } = useI18n();

// Define component props: open state and optional type data
defineProps<{
  open: boolean;
  type?: { id: number };
}>();

// Setup toast notification
const { toast } = useToast();
// Emit event to update `open` prop externally
const emit = defineEmits(['update:open']);

// --- DELETE HANDLER ---
// Function to delete a type by its ID
const deleteType = async (type: { id: number }) => {
  router.delete(`/dashboard/question/${type.id}`, {
    onSuccess: () => {
      // Close dialog and show success toast on successful deletion
      emit('update:open', false);
      toast({
        title: t('question.deleteQuestionsDialog.toast.success.title'),
        description: t('question.deleteQuestionsDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      // Show error toast and log errors on failure
      toast({
        title: t('question.deleteQuestionsDialog.toast.error.title'),
        description: t('question.deleteQuestionsDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
};
</script>

<template>
  <!-- Dialog bound to `open` prop -->
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <!-- Dialog title and description -->
        <DialogTitle>{{ t('question.deleteQuestionsDialog.title') }}</DialogTitle>
        <DialogDescription>{{ t('question.deleteQuestionsDialog.description') }}</DialogDescription>
      </DialogHeader>
      <!-- Delete action button -->
      <Button class="bg-red-500 hover:bg-red-400" @click="deleteType(type)">
        {{ t('question.deleteQuestionsDialog.delete') }}
      </Button>
    </DialogContent>
  </Dialog>
  <!-- Toast container for notifications -->
  <Toaster />
</template>
