<script setup lang="ts">
// Import Inertia head component for setting page title
import { Head } from '@inertiajs/vue3';
// Vue reactive utilities
import { computed, ref } from 'vue';
// Internationalization composable for translations\import { useI18n } from 'vue-i18n';
import { useI18n } from 'vue-i18n';
// Layout and dialog components for templates
import AppLayout from '@/layouts/AppLayout.vue';
import CreateTemplateDialog from '../components/CreateTemplateDialog.vue';
import DeleteTemplateDialog from '../components/DeleteTemplateDialog.vue';
import EditTemplateDialog from '../components/EditTemplateDialog.vue';
import ImportTemplatePopover from '../components/ImportTemplatePopover.vue';
import ViewTemplateDialog from '../components/ViewTemplateDialog.vue';

// UI components for buttons, pagination, popovers, selects, tables, tooltips
import { Button } from '@/components/ui/button';
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from '@/components/ui/pagination';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { Ellipsis, FileUp, PlusCircle } from 'lucide-vue-next';

import { BreadcrumbItem } from '@/types';

// Setup i18n translation function
const { t } = useI18n();

// --- Props ---
// Define incoming props for templates list and types
const props = defineProps<{
  templates: {
    id: string;
    title: string;
    created_at: string;
    types: { id: number; title: string }[];
    canModify: boolean;
  }[];
  types: { id: number; title: string }[];
}>();

// --- Breadcrumbs ---
// Breadcrumb trail for navigation
const breadcrumbs: BreadcrumbItem[] = [
  { title: t('template.breadcrumbs.template'), href: '/dashboard/template' },
];

// --- YEAR FILTER STATE ---
// Selected year for filtering ("all" shows every year)
const selectedYear = ref<'all' | string>('all');

// Compute unique years from templates, sorted descending
const years = computed<number[]>(() => {
  const set = new Set<number>(props.templates.map((i) => new Date(i.created_at).getFullYear()));
  return Array.from(set).sort((a, b) => b - a);
});

// Filter templates by selected year
const filteredTemplates = computed(() =>
  props.templates.filter((i) => {
    if (selectedYear.value === 'all') return true;
    return new Date(i.created_at).getFullYear() === Number(selectedYear.value);
  }),
);

// --- PAGINATION ---
const pageSize = 5; // Number of items per page
const currentPage = ref(1); // Current active page

// Compute templates for current page
const paginatedTemplates = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredTemplates.value.slice(start, start + pageSize);
});

// --- EXPORT ---
// Trigger CSV export via backend route
const exportTemplates = () => {
  window.location.href = route('template.export');
};

// --- DATE FORMATTING ---
// Format ISO date string into French locale
const formatDate = (iso: string) =>
  new Date(iso).toLocaleString('fr-FR', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });

// --- DIALOG STATE ---
// State variables for controlling dialog visibility
const isCreateDialogOpen = ref(false);
const isViewDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
// Holds currently selected template for view/edit/delete
const selectedTemplate = ref<any>(null);

// Open handlers for each dialog type
function openCreateDialog() {
  isCreateDialogOpen.value = true;
}
function openViewDialog(item: any) {
  selectedTemplate.value = item;
  isViewDialogOpen.value = true;
}
function openEditDialog(item: any) {
  selectedTemplate.value = item;
  isEditDialogOpen.value = true;
}
function openDeleteDialog(item: any) {
  selectedTemplate.value = item;
  isDeleteDialogOpen.value = true;
}
</script>

<template>
  <!-- Set page title -->
  <Head title="Dashboard" />

  <!-- Main app layout with breadcrumbs -->
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="m-4 flex flex-col gap-4 rounded-xl p-4">
      <!-- Page heading -->
      <h1 class="text-3xl font-bold">{{ t('template.title') }}</h1>

      <!-- Toolbar: filter by year and action buttons -->
      <div class="flex items-center justify-between">
        <!-- Year filter dropdown -->
        <div class="flex w-1/3 items-center gap-2">
          <div div class="w-1/2 items-center">
            <span class="text-sm font-medium text-muted-foreground">
              {{ $t('dashboard.filterByYear') }}
            </span>
          </div>
          <Select v-model="selectedYear">
            <SelectTrigger>
              <SelectValue :placeholder="t('template.filterYearPlaceholder')" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">{{ t('template.allYears') }}</SelectItem>
              <SelectItem v-for="year in years" :key="year" :value="year.toString()">
                {{ year }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Import, export and add buttons -->
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1">
            <ImportTemplatePopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger as-child>
                  <Button @click="exportTemplates" variant="outline" size="sm" class="h-8 gap-1">
                    <FileUp class="h-3.5 w-3.5" />
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('template.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <Button @click="openCreateDialog" size="sm" class="h-8 gap-1">
            <PlusCircle class="h-3.5 w-3.5" />
            <span>{{ t('template.add') }}</span>
          </Button>
        </div>
      </div>

      <!-- Template table -->
      <div v-if="paginatedTemplates.length">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ t('template.table.title') }}</TableHead>
              <TableHead>{{ t('template.table.types') }}</TableHead>
              <TableHead>{{ t('template.table.createdAt') }}</TableHead>
              <TableHead class="text-right">{{ t('template.table.actions') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="i in paginatedTemplates" :key="i.id">
              <TableCell>{{ i.title }}</TableCell>
              <TableCell>
                <ul>
                  <li v-for="t in i.types" :key="t.id">- {{ t.title }}</li>
                </ul>
              </TableCell>
              <TableCell>{{ formatDate(i.created_at) }}</TableCell>
              <TableCell class="text-right">
                <!-- Action popover for each template row -->
                <Popover>
                  <PopoverTrigger as-child>
                    <button class="rounded-md p-2 hover:bg-background">
                      <Ellipsis class="h-5 w-5" />
                    </button>
                  </PopoverTrigger>
                  <PopoverContent class="w-40 rounded-md border p-2 shadow-lg">
                    <button
                      @click="openViewDialog(i)"
                      class="w-full rounded px-3 py-2 text-left hover:bg-gray-100"
                    >
                      {{ t('template.table.actionsPopover.view') }}
                    </button>
                    <button
                      @click="openEditDialog(i)"
                      class="w-full rounded px-3 py-2 text-left hover:bg-gray-100"
                      :disabled="!i.canModify"
                    >
                      {{ t('template.table.actionsPopover.edit') }}
                    </button>
                    <button
                      @click="openDeleteDialog(i)"
                      class="w-full rounded px-3 py-2 text-left text-red-600 hover:bg-red-100"
                      :disabled="!i.canModify"
                    >
                      {{ t('template.table.actionsPopover.delete') }}
                    </button>
                  </PopoverContent>
                </Popover>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

        <!-- Pagination controls -->
        <div
          v-if="Math.ceil(filteredTemplates.length / pageSize) > 1"
          class="flex justify-center pt-4"
        >
          <Pagination
            v-model:page="currentPage"
            :items-per-page="pageSize"
            :total="filteredTemplates.length"
            :sibling-count="1"
            show-edges
          >
            <PaginationList v-slot="{ items }" class="flex gap-1">
              <PaginationFirst />
              <PaginationPrev />
              <template v-for="(item, i) in items" :key="i">
                <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                  <Button
                    class="h-9 w-9 p-0"
                    :variant="item.value === currentPage ? 'default' : 'outline'"
                  >
                    {{ item.value }}
                  </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else />
              </template>
              <PaginationNext />
              <PaginationLast />
            </PaginationList>
          </Pagination>
        </div>
      </div>

      <!-- Empty state when no templates found -->
      <div v-else class="flex h-96 flex-col items-center justify-center">
        <h2 class="text-3xl font-bold">{{ t('template.noItems') }}</h2>
        <p class="text-xl text-gray-500">{{ t('template.noItemsDescription') }}</p>
      </div>
    </div>

    <!-- Dialog components mounted outside table -->
    <CreateTemplateDialog v-model:open="isCreateDialogOpen" :types="props.types" />
    <ViewTemplateDialog v-model:open="isViewDialogOpen" :template="selectedTemplate" />
    <EditTemplateDialog
      v-model:open="isEditDialogOpen"
      :template="selectedTemplate"
      :types="props.types"
    />
    <DeleteTemplateDialog v-model:open="isDeleteDialogOpen" :template="selectedTemplate" />
  </AppLayout>
</template>
