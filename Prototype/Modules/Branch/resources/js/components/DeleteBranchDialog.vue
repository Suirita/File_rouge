<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    open: boolean;
    branch?: { id: number; name: string; questions: { id: number; question: string }[] };
}>();

const { toast } = useToast();

const emit = defineEmits(['update:open']);

const deleteBranch = async (branch: { id: number; name: string; questions: { id: number; question: string }[] }) => {
    router.delete(`/dashboard/branch/${branch.id}`, {
        onSuccess: () => {
            emit('update:open', false);
            toast({ title: t('deleteBranchDialog.toast.success.title'), description: t('deleteBranchDialog.toast.success.description') });
        },
        onError: (errors) => {
            toast({ title: t('deleteBranchDialog.toast.error.title'), description: t('deleteBranchDialog.toast.error.description') });
            console.error(errors);
        },
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle> {{ t('deleteBranchDialog.title') }} </DialogTitle>
                <DialogDescription> {{ t('deleteBranchDialog.description') }} </DialogDescription>
            </DialogHeader>
            <Button class="bg-red-500 hover:bg-red-400" @click="deleteBranch(branch)"> {{ t('deleteBranchDialog.delete') }} </Button>
        </DialogContent>
    </Dialog>
    <Toaster />
</template>
