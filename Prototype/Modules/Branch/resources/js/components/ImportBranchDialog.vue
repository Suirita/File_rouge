<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { useI18n } from 'vue-i18n';
import { z } from 'zod';

const { t } = useI18n();

defineProps<{ open: boolean }>();

const { toast } = useToast();

const emit = defineEmits(['update:open']);

const branchSchema = toTypedSchema(
    z.object({
        file: z.instanceof(File),
    }),
);

const { handleSubmit, errors, resetForm, setFieldValue } = useForm({
    validationSchema: branchSchema,
    initialValues: {
        file: undefined,
    },
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        setFieldValue('file', target.files[0]);
    }
};

const importBranches = handleSubmit(async (values) => {
    router.post('/dashboard/branch/import', values, {
        forceFormData: true,
        onSuccess: () => {
            emit('update:open', false);
            toast({
                title: t('importBranchDialog.toast.success.title'),
                description: t('importBranchDialog.toast.success.description'),
            });
            resetForm();
        },
        onError: (errors) => {
            toast({
                title: t('importBranchDialog.toast.error.title'),
                description: t('importBranchDialog.toast.error.description'),
            });
            console.error(errors);
        },
    });
});
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-h-[600px] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>{{ t('importBranchDialog.title') }}</DialogTitle>
                <DialogDescription>{{ t('importBranchDialog.description') }}</DialogDescription>
            </DialogHeader>
            <form @submit.prevent="importBranches" class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="file">{{ t('importBranchDialog.file') }}</Label>
                    <Input id="file" type="file" @change="handleFileChange" />
                    <p v-if="errors.file" class="text-sm text-red-500">{{ errors.file }}</p>
                </div>
                <Button type="submit" class="mt-4 w-full">{{ t('importBranchDialog.import') }}</Button>
            </form>
        </DialogContent>
    </Dialog>
    <Toaster />
</template>
