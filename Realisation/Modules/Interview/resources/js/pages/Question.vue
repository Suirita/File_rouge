<script setup lang="ts">
// Import UI components: Button and Input
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
// Import pagination components
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
// Import popover components
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
// Import table components
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
// Import tooltip components
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
// Main layout and type definitions
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
// Inertia head for setting title
import { Head } from '@inertiajs/vue3';
// Vue reactivity and i18n
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

// Dialog components for type CRUD
import CreateQuestionsDialog from '../components/CreateQuestionsDialog.vue';
import DeleteQuestionsDialog from '../components/DeleteQuestionsDialog.vue';
import EditQuestionsDialog from '../components/EditQuestionsDialog.vue';
import ImportQuestionsPopover from '../components/ImportQuestionsPopover.vue';
import ViewQuestionsDialog from '../components/ViewQuestionsDialog.vue';

// Icons
import { Ellipsis, FileUp, PlusCircle } from 'lucide-vue-next';

// Setup translation function
const { t } = useI18n();

// --- BREADCRUMBS ---
// Define breadcrumb trail
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: t('question.breadcrumbs.questions'),
    href: '/dashboard/question',
  },
];

// --- PROPS ---
// Incoming prop: list of type objects
const props = defineProps<{
  types: {
    id: string;
    title: string;
    canModify: boolean;
    questions: { id: number; title: string }[];
  }[];
}>();

// --- SEARCH STATE ---
// Reactive search query string
const searchQuery = ref('');

// Compute filtered types based on search query
const filteredTypes = computed(() => {
  const q = searchQuery.value.trim().toLowerCase();
  if (!q) {
    return props.types;
  }
  return props.types.filter((t) => t.title.toLowerCase().includes(q));
});

// --- PAGINATION ---
const pageSize = 5; // items per page
const currentPage = ref(1); // current page number
// Compute types for current page slice
const paginatedTypes = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredTypes.value.slice(start, start + pageSize);
});

// Compute last page based on filtered types and page size
const lastPage = computed(() => {
  return Math.max(1, Math.ceil(filteredTypes.value.length / pageSize));
});

// Watch filtered types and last page to update current page if needed
watch(
  [filteredTypes, lastPage],
  () => {
    // If our current page is beyond the last page, jump back:
    if (currentPage.value > lastPage.value) {
      currentPage.value = lastPage.value;
    }
  },
  { immediate: true },
);

// --- EXPORT ---
// Trigger CSV export via backend route
const exportUsers = () => {
  window.location.href = route('question.export');
};

// --- DIALOG STATE ---
// Booleans to control dialog visibility
const isCreateQuestionsDialogOpen = ref(false);
const isViewQuestionsDialogOpen = ref(false);
const isEditQuestionsDialogOpen = ref(false);
const isDeleteQuestionsDialogOpen = ref(false);
// Holds selected type for view/edit/delete
const type = ref<any>(null);

// --- DIALOG OPEN HANDLERS ---
function openCreateQuestionsDialog() {
  isCreateQuestionsDialogOpen.value = true;
}
function openViewQuestionsDialog(t: any) {
  type.value = t;
  isViewQuestionsDialogOpen.value = true;
}
function openEditQuestionsDialog(t: any) {
  type.value = t;
  isEditQuestionsDialogOpen.value = true;
}
function openDeleteQuestionsDialog(t: any) {
  type.value = t;
  isDeleteQuestionsDialogOpen.value = true;
}
</script>

<template>
  <!-- Set page title -->
  <Head title="Dashboard" />

  <!-- App layout with breadcrumbs -->
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="m-4 flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Page heading -->
      <h1 class="text-3xl font-bold">{{ t('question.title') }}</h1>

      <!-- Toolbar: search input and action buttons -->
      <div class="flex items-center justify-between">
        <!-- Search field -->
        <div class="flex w-2/4 items-center gap-4">
          <Input v-model="searchQuery" type="text" :placeholder="t('question.searchPlaceholder')" />
        </div>

        <!-- Import, export, add buttons -->
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1">
            <ImportQuestionsPopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger as-child>
                  <Button @click="exportUsers" size="sm" variant="outline" class="h-8 gap-1">
                    <FileUp class="h-3.5 w-3.5" />
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('question.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
          <Button size="sm" class="h-8 gap-1" @click="openCreateQuestionsDialog">
            <PlusCircle class="h-3.5 w-3.5" />
            <span>{{ t('question.add') }}</span>
          </Button>
        </div>
      </div>

      <!-- Types table or empty state -->
      <div v-if="paginatedTypes.length > 0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ t('question.table.type') }}</TableHead>
              <TableHead>{{ t('question.table.questions') }}</TableHead>
              <TableHead class="text-right">{{ t('question.table.actions') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="type in paginatedTypes" :key="type.id">
              <!-- Type title -->
              <TableCell>{{ type.title }}</TableCell>
              <!-- List of questions under type -->
              <TableCell>
                <ul>
                  <li v-for="q in type.questions" :key="q.id">- {{ q.title }}</li>
                </ul>
              </TableCell>
              <!-- Actions popover -->
              <TableCell class="text-right">
                <Popover>
                  <PopoverTrigger>
                    <button class="rounded-md p-2 hover:bg-background">
                      <Ellipsis class="h-5 w-5" />
                    </button>
                  </PopoverTrigger>
                  <PopoverContent class="w-40 rounded-md border p-2 shadow-lg">
                    <button
                      class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100"
                      @click="openViewQuestionsDialog(type)"
                    >
                      {{ t('question.table.actionsPopover.view') }}
                    </button>
                    <button
                      class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100"
                      @click="openEditQuestionsDialog(type)"
                      :disabled="!type.canModify"
                    >
                      {{ t('question.table.actionsPopover.edit') }}
                    </button>
                    <button
                      class="w-full rounded-md px-3 py-2 text-left text-red-600 hover:bg-red-100"
                      @click="openDeleteQuestionsDialog(type)"
                      :disabled="!type.canModify"
                    >
                      {{ t('question.table.actionsPopover.delete') }}
                    </button>
                  </PopoverContent>
                </Popover>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

        <!-- Pagination controls if needed -->
        <div
          v-if="Math.ceil(filteredTypes.length / pageSize) > 1"
          class="flex items-center justify-center pt-4"
        >
          <Pagination
            v-model:page="currentPage"
            :items-per-page="pageSize"
            :total="filteredTypes.length"
            :sibling-count="1"
            show-edges
          >
            <PaginationList v-slot="{ items }" class="flex items-center gap-1">
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
      <div v-else>
        <!-- Empty state when no types -->
        <div class="flex h-96 flex-col items-center justify-center">
          <h2 class="text-2xl font-bold">{{ t('question.noTypes') }}</h2>
          <p class="text-lg text-gray-500">{{ t('question.noTypesDescription') }}</p>
        </div>
      </div>
    </div>

    <!-- Dialog components for create, view, edit, delete -->
    <CreateQuestionsDialog v-model:open="isCreateQuestionsDialogOpen" />
    <ViewQuestionsDialog v-model:open="isViewQuestionsDialogOpen" :type="type" />
    <EditQuestionsDialog v-model:open="isEditQuestionsDialogOpen" :type="type" />
    <DeleteQuestionsDialog v-model:open="isDeleteQuestionsDialogOpen" :type="type" />
  </AppLayout>
</template>
