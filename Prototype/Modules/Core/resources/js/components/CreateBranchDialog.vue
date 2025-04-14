<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { Plus, Trash } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { Ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { z } from 'zod';

const { t } = useI18n();

defineProps<{ open: boolean }>();

const { toast } = useToast();
const emit = defineEmits(['update:open']);

const branchSchema = toTypedSchema(
    z.object({
        name: z.string().min(1, t('createBranchDialog.branchSchema.name')),
        questions: z
            .array(z.string().min(1, t('createBranchDialog.branchSchema.questions')))
            .nonempty(t('createBranchDialog.branchSchema.questions')),
    }),
);

const { defineField, handleSubmit, errors, resetForm } = useForm({
    validationSchema: branchSchema,
    initialValues: {
        name: '',
        questions: [''],
    },
});

const [name, nameAttrs] = defineField('name');

const [questions] = defineField('questions') as unknown as [Ref<string[]>, unknown];

const addBranch = handleSubmit(async (values) => {
    router.post('/dashboard/branch', values, {
        onSuccess: () => {
            emit('update:open', false);
            toast({
                title: t('createBranchDialog.toast.success.title'),
                description: t('createBranchDialog.toast.success.description'),
            });
            resetForm();
        },
        onError: (errors) => {
            toast({
                title: t('createBranchDialog.toast.error.title'),
                description: t('createBranchDialog.toast.error.description'),
            });
            console.error(errors);
        },
    });
});

const addQuestion = () => {
    questions.value = [...(questions.value ?? []), ''];
};

const removeQuestion = (index: number) => {
    if (questions.value && questions.value.length > 1) {
        questions.value.splice(index, 1);
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-h-[600px] overflow-y-auto">
            <DialogHeader>
                <DialogTitle> {{ t('createBranchDialog.title') }} </DialogTitle>
                <DialogDescription> {{ t('createBranchDialog.description') }} </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="addBranch" class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name"> {{ t('createBranchDialog.name') }} </Label>
                    <Input id="name" v-model="name" v-bind="nameAttrs" />
                    <p v-if="errors.name" class="text-sm text-red-500">{{ errors.name }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <Label> {{ t('createBranchDialog.questions') }} </Label>
                        <Button type="button" @click="addQuestion">
                            <Plus />
                        </Button>
                    </div>
                    <div v-for="(question, index) in questions" :key="index" class="flex items-center gap-2">
                        <Input v-model="questions[index]" />
                        <Button v-if="index > 0" type="button" @click="removeQuestion(index)" class="bg-red-500 hover:bg-red-400">
                            <Trash />
                        </Button>
                    </div>
                    <p v-if="errors.questions" class="text-sm text-red-500">{{ errors.questions }}</p>
                </div>
                <Button type="submit" class="mt-4 w-full"> {{ t('createBranchDialog.add') }} </Button>
            </form>
        </DialogContent>
    </Dialog>
    <Toaster />
</template>
