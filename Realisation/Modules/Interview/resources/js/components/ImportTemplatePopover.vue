<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { FileDown } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { useI18n } from 'vue-i18n';
import * as z from 'zod';

const { t } = useI18n();

const { toast } = useToast();

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
  router.post('/dashboard/template/import', values, {
    forceFormData: true,
    onSuccess: () => {
      toast({
        title: t('template.importTemplatePopover.toast.success.title'),
        description: t(
          'template.importTemplatePopover.toast.success.description',
        ),
      });
      resetForm();
    },
    onError: (errors) => {
      toast({
        title: t('template.importTemplatePopover.toast.error.title'),
        description: t(
          'template.importTemplatePopover.toast.error.description',
        ),
      });
      console.error(errors);
    },
  });
});
</script>

<template>
  <Popover>
    <PopoverTrigger>
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger>
            <Button size="sm" variant="outline" class="h-8 gap-1">
              <FileDown class="h-3.5 w-3.5" />
            </Button>
          </TooltipTrigger>
          <TooltipContent>
            <p>{{ t('template.import') }}</p>
          </TooltipContent>
        </Tooltip>
      </TooltipProvider>
    </PopoverTrigger>
    <PopoverContent class="w-96 rounded-md border p-4 shadow-lg">
      <header class="mb-4">
        <h1 class="text-lg font-bold">
          {{ t('template.importTemplatePopover.title') }}
        </h1>
        <p class="text-sm text-gray-600">
          {{ t('template.importTemplatePopover.description') }}
        </p>
      </header>
      <form
        @submit.prevent="importBranches"
        class="flex flex-col gap-4"
      >
        <div class="flex flex-col gap-2">
          <Label for="file">
            {{ t('template.importTemplatePopover.file') }}
          </Label>
          <Input id="file" type="file" @change="handleFileChange" />
          <p v-if="errors.file" class="text-sm text-red-500">
            {{ errors.file }}
          </p>
        </div>
        <Button type="submit" class="w-full">
          {{ t('template.importTemplatePopover.import') }}
        </Button>
      </form>
    </PopoverContent>
  </Popover>
  <Toaster />
</template>
