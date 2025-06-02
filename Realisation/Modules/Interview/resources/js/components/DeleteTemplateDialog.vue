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
  template?: {
    id: number;
  };
}>();

const { toast } = useToast();

const emit = defineEmits(['update:open']);

const deleteTemplate = async (template: { id: number }) => {
  router.delete(`/dashboard/template/${template.id}`, {
    onSuccess: () => {
      emit('update:open', false);
      toast({
        title: t('template.deleteTemplateDialog.toast.success.title'),
        description: t('template.deleteTemplateDialog.toast.success.description'),
      });
    },
    onError: (errors) => {
      toast({
        title: t('template.deleteTemplateDialog.toast.error.title'),
        description: t('template.deleteTemplateDialog.toast.error.description'),
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
          {{ t('template.deleteTemplateDialog.title') }}
        </DialogTitle>
        <DialogDescription>
          {{ t('template.deleteTemplateDialog.description') }}
        </DialogDescription>
      </DialogHeader>
      <Button class="bg-red-500 hover:bg-red-400" @click="deleteTemplate(template)">
        {{ t('template.deleteTemplateDialog.delete') }}
      </Button>
    </DialogContent>
  </Dialog>
  <Toaster />
</template>
