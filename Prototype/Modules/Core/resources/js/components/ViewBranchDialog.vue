<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    open: boolean;
    branch?: { name: string; questions: { id: number; question: string }[] };
}>();

const emit = defineEmits(['update:open']);
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-w-lg rounded-lg p-6 shadow-lg">
            <DialogHeader>
                <DialogTitle class="text-xl font-semibold">
                    {{ t('viewBranchDialog.title') }}: <span class="text-2xl font-bold text-gray-800">{{ branch?.name }}</span>
                </DialogTitle>
                <DialogDescription class="mt-6 text-lg text-black">{{ t('viewBranchDialog.description') }}: </DialogDescription>
            </DialogHeader>
            <ul v-if="branch?.questions?.length" class="space-y-2">
                <li v-for="question in branch.questions" :key="question.id" class="rounded-md bg-gray-100 p-3">
                    {{ question.question }}
                </li>
            </ul>
            <p v-else class="text-center text-gray-500">{{ t('viewBranchDialog.noQuestions') }}</p>
        </DialogContent>
    </Dialog>
</template>
