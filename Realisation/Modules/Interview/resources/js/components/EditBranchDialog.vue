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
import { computed, ref, watch, watchEffect } from 'vue';
import { useI18n } from 'vue-i18n';
import { z } from 'zod';

const { t } = useI18n();

const props = defineProps<{
    open: boolean;
    branch?: { id: number; name: string; questions: { id: number; question: string }[] };
}>();

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

const { handleSubmit, errors, resetForm, setValues, values, setFieldValue } = useForm({
    validationSchema: branchSchema,
    initialValues: {
        name: '',
        questions: [''],
    },
});

const localQuestions = ref<string[]>([]);

watchEffect(() => {
    if (props.branch) {
        const qs = props.branch.questions.length ? props.branch.questions.map((q) => q.question) : [''];
        setValues({
            name: props.branch.name,
            questions: qs,
        });
        localQuestions.value = qs;
    }
});

watch(
    localQuestions,
    (newVal) => {
        setFieldValue('questions', newVal);
    },
    { deep: true },
);

const nameModel = computed({
    get: () => values.name,
    set: (val: string) => setFieldValue('name', val),
});

const editBranch = handleSubmit(async (formValues) => {
    if (!props.branch) return;

    formValues.questions = localQuestions.value;
    router.put(`/dashboard/branch/${props.branch.id}`, formValues, {
        onSuccess: () => {
            emit('update:open', false);
            toast({ title: t('editBranchDialog.toast.success.title'), description: t('editBranchDialog.toast.success.description') });
            resetForm();
            localQuestions.value = [''];
        },
        onError: (err) => {
            toast({ title: t('editBranchDialog.toast.error.title'), description: t('editBranchDialog.toast.error.description') });
            console.error(err);
        },
    });
});

const addQuestion = () => {
    localQuestions.value.push('');
};

const removeQuestion = (index: number) => {
    if (localQuestions.value.length > 1) {
        localQuestions.value.splice(index, 1);
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-h-[600px] overflow-y-auto">
            <DialogHeader>
                <DialogTitle> {{ t('editBranchDialog.title') }}: {{ props.branch?.name || '' }} </DialogTitle>
                <DialogDescription> {{ t('editBranchDialog.description') }} </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="editBranch" class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name"> {{ t('editBranchDialog.name') }} </Label>
                    <Input id="name" v-model="nameModel" />
                    <p v-if="errors.name" class="text-sm text-red-500">{{ errors.name }}</p>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <Label> {{ t('editBranchDialog.questions') }} </Label>
                        <Button type="button" @click="addQuestion">
                            <Plus />
                        </Button>
                    </div>
                    <div v-for="(question, index) in localQuestions" :key="index" class="flex items-center gap-2">
                        <Input v-model="localQuestions[index]" />
                        <Button v-if="index > 0" type="button" @click="removeQuestion(index)" class="bg-red-500 hover:bg-red-400">
                            <Trash />
                        </Button>
                    </div>
                    <p v-if="errors.questions" class="text-sm text-red-500">{{ errors.questions }}</p>
                </div>

                <Button type="submit" class="mt-4 w-full"> {{ t('editBranchDialog.edit') }} </Button>
            </form>
        </DialogContent>
    </Dialog>
    <Toaster />
</template>
