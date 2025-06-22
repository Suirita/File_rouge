<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
  open: boolean;
  interview?: {
    id: number;
  };
}>();

const { toast } = useToast();

const emit = defineEmits(['update:open']);

const deleteInterview = async (interview: { id: number }) => {
  router.delete(`/dashboard/interview/${interview.id}`, {
    onSuccess: () => {
      emit('update:open', false);
      toast({
        title: t('interview.deleteInterviewDialog.toast.success.title'),
        description: t('interview.deleteInterviewDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      toast({
        title: t('interview.deleteInterviewDialog.toast.error.title'),
        description: t('interview.deleteInterviewDialog.toast.error.description'),
      });
      console.error(errors);
    },
  });
};
</script>

<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>
          {{ t('interview.deleteInterviewDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('interview.deleteInterviewDialog.description') }}
        </DialogDescription>
      </DialogHeader>
      <Button class="bg-red-500 hover:bg-red-400" @click="deleteInterview(interview)">
        {{ t('interview.deleteInterviewDialog.delete') }}
      </Button>
    </DialogContent>
  </Dialog>
  <Toaster />
</template>
