```vue
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import AppLayout from '@/layouts/AppLayout.vue';
import DeleteInterviewDialog from '../components/DeleteInterviewDialog.vue';
import EditInterviewDialog from '../components/EditInterviewDialog.vue';
import ImportInterviewPopover from '../components/ImportInterviewPopover.vue';
import StartInterviewDialog from '../components/StartInterviewDialog.vue';
import ViewInterviewDialog from '../components/ViewInterviewDialog.vue';

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
import { Ellipsis, FileUp } from 'lucide-vue-next';
// Add new imports for Command components
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
} from '@/components/ui/command';
import { ChevronDown } from 'lucide-vue-next';

import { BreadcrumbItem } from '@/types';

const { t } = useI18n();

const props = defineProps<{
  interviews: {
    id: string;
    status: string;
    date: string;
    created_at: string;
    evaluations: {
      evaluator: {
        id: number;
        name: string;
      };
      question: {
        id: number;
        title: string;
      };
      score: number;
      remarks: string;
    }[];
    candidate: {
      id: number;
      name: string;
    };
    template: {
      id: number;
      title: string;
      types: {
        id: number;
        title: string;
        questions: {
          id: number;
          title: string;
          type: string;
        }[];
      }[];
    };
  }[];
  templates: {
    id: number;
    title: string;
  }[];
}>();

const getAvgScore = (evs: { score: number }[]): string => {
  if (!evs || evs.length === 0) return '—';
  const sum = evs.reduce((acc, e) => acc + e.score, 0);
  return (sum / evs.length).toFixed(1);
};

const breadcrumbs: BreadcrumbItem[] = [
  { title: t('interview.breadcrumbs'), href: '/dashboard/interview' },
];

// Remove searchTerm ref since we're replacing it
// const searchTerm = ref<string>('');

// Add new state for the searchable select
const selectedCandidateId = ref('all');
const isComboboxOpen = ref(false);

const uniqueCandidates = computed(() => {
  const map = new Map<number, { id: number; name: string }>();
  props.interviews.forEach((i) => {
    const cand = i.candidate;
    if (!map.has(cand.id)) {
      map.set(cand.id, cand);
    }
  });
  return Array.from(map.values()).sort((a, b) => a.name.localeCompare(b.name));
});

const candidateOptions = computed(() => [
  { id: 'all', name: t('interview.allCandidates') },
  ...uniqueCandidates.value,
]);

const selectedCandidateName = computed(() => {
  const selected = candidateOptions.value.find((c) => c.id === selectedCandidateId.value);
  return selected ? selected.name : t('interview.selectCandidate');
});

function selectCandidate(id: string | number) {
  selectedCandidateId.value = id;
  isComboboxOpen.value = false;
}

const selectedTemplate = ref<'all' | string>('all');
const selectedStatus = ref<'all' | string>('all');
const selectedDate = ref<'all' | string>('all');

const templateOptions = computed<{ id: number; title: string }[]>(() => {
  const map = new Map<number, string>();
  props.interviews.forEach((i) => {
    const tmpl = i.template;
    if (tmpl && !map.has(tmpl.id)) {
      map.set(tmpl.id, tmpl.title);
    }
  });
  return Array.from(map.entries())
    .map(([id, title]) => ({ id, title }))
    .sort((a, b) => a.title.localeCompare(b.title));
});

const statusOptions = computed<string[]>(() => {
  const set = new Set<string>();
  props.interviews.forEach((i) => {
    if (i.status) {
      set.add(i.status);
    }
  });
  return Array.from(set).sort((a, b) => a.localeCompare(b));
});

const dateOptions = computed<string[]>(() => {
  const set = new Set<string>();
  props.interviews.forEach((i) => {
    const isoDate = i.date.split('T')[0];
    set.add(isoDate);
  });
  return Array.from(set).sort((a, b) => a.localeCompare(b));
});

const filteredInterviews = computed(() => {
  if (!props.interviews) return [];
  return props.interviews.filter((i) => {
    // Replace searchTerm filter with selectedCandidateId filter
    if (selectedCandidateId.value !== 'all' && i.candidate.id !== selectedCandidateId.value) {
      return false;
    }
    if (selectedTemplate.value !== 'all' && i.template.id !== Number(selectedTemplate.value)) {
      return false;
    }
    if (selectedStatus.value !== 'all' && i.status !== selectedStatus.value) {
      return false;
    }
    if (selectedDate.value !== 'all' && i.date.split('T')[0] !== selectedDate.value) {
      return false;
    }
    return true;
  });
});

const pageSize = 5;
const currentPage = ref(1);

const paginatedInterviews = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredInterviews.value.slice(start, start + pageSize);
});

const exportInterviews = () => {
  window.location.href = route('interview.export');
};

const formatDate = (iso: string) =>
  new Date(iso).toLocaleString('fr-FR', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });

const clearFilters = () => {
  // Update to reset selectedCandidateId instead of searchTerm
  selectedCandidateId.value = 'all';
  selectedTemplate.value = 'all';
  selectedStatus.value = 'all';
  selectedDate.value = 'all';
  currentPage.value = 1;
};

const isStartDialogOpen = ref(false);
const isViewDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const selectedInterview = ref<any>(null);

function openStartDialog() {
  isStartDialogOpen.value = true;
}
function openViewDialog(item: any) {
  selectedInterview.value = item;
  isViewDialogOpen.value = true;
}
function openEditDialog(item: any) {
  selectedInterview.value = item;
  isEditDialogOpen.value = true;
}
function openDeleteDialog(item: any) {
  selectedInterview.value = item;
  isDeleteDialogOpen.value = true;
}
</script>

<template>
  <Head title="Dashboard" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="m-4 flex flex-col gap-4 rounded-xl p-4">
      <div class="flex justify-between">
        <h1 class="text-3xl font-bold">{{ t('interview.title') }}</h1>
        <Button @click="openStartDialog">{{ t('interview.start') }}</Button>
      </div>
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
          <!-- Replace Input with searchable select -->
          <div class="w-64">
            <Popover v-model:open="isComboboxOpen">
              <PopoverTrigger as-child>
                <Button variant="outline" class="w-full justify-between">
                  {{ selectedCandidateName }}
                  <ChevronDown class="ml-2 h-4 w-4" />
                </Button>
              </PopoverTrigger>
              <PopoverContent class="max-h-96 w-64 overflow-y-auto px-0 py-0">
                <Command>
                  <CommandInput :placeholder="t('interview.searchCandidate')" />
                  <CommandEmpty>{{ t('interview.noCandidateFound') }}</CommandEmpty>
                  <CommandGroup>
                    <CommandItem
                      v-for="candidate in candidateOptions"
                      :key="candidate.id"
                      :value="candidate.name"
                      @click="selectCandidate(candidate.id)"
                    >
                      {{ candidate.name }}
                    </CommandItem>
                  </CommandGroup>
                </Command>
              </PopoverContent>
            </Popover>
          </div>

          <div>
            <Select v-model="selectedTemplate">
              <SelectTrigger class="w-40">
                <SelectValue :placeholder="t('interview.filterTemplatePlaceholder')" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">{{ t('interview.allTemplates') }}</SelectItem>
                <SelectItem
                  v-for="tmpl in templateOptions"
                  :key="tmpl.id"
                  :value="tmpl.id.toString()"
                >
                  {{ tmpl.title }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Select v-model="selectedStatus">
              <SelectTrigger class="w-40">
                <SelectValue :placeholder="t('interview.filterStatusPlaceholder')" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">{{ t('interview.allStatuses') }}</SelectItem>
                <SelectItem v-for="status in statusOptions" :key="status" :value="status">
                  {{ t(`interview.statusOptions.${status}`) }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Select v-model="selectedDate">
              <SelectTrigger class="w-40">
                <SelectValue :placeholder="t('interview.filterDatePlaceholder')" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">{{ t('interview.allDates') }}</SelectItem>
                <SelectItem v-for="date in dateOptions" :key="date" :value="date">
                  {{ date }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Button @click="clearFilters" variant="outline">
              {{ t('interview.clearFilters') }}
            </Button>
          </div>
        </div>

        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1">
            <ImportInterviewPopover />
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger as-child>
                  <Button @click="exportInterviews" variant="outline" size="sm" class="h-8 gap-1">
                    <FileUp class="h-3.5 w-3.5" />
                  </Button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{{ t('interview.export') }}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>
        </div>
      </div>

      <div v-if="paginatedInterviews.length">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ t('interview.table.candidate') }}</TableHead>
              <TableHead>{{ t('interview.table.template') }}</TableHead>
              <TableHead>{{ t('interview.table.status') }}</TableHead>
              <TableHead>{{ t('interview.table.score') }}</TableHead>
              <TableHead>{{ t('interview.table.date') }}</TableHead>
              <TableHead class="text-right">{{ t('interview.table.actions') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="i in paginatedInterviews" :key="i.id">
              <TableCell>{{ i.candidate.name }}</TableCell>
              <TableCell>{{ i.template.title }}</TableCell>
              <TableCell>
                <span
                  :class="[
                    'rounded-full px-2 py-1 text-xs font-medium',
                    i.status === 'completed'
                      ? 'bg-blue-300'
                      : i.status === 'pending'
                        ? 'bg-amber-300'
                        : 'bg-rose-400',
                  ]"
                >
                  {{ t(`interview.statusOptions.${i.status}`) }}
                </span>
              </TableCell>
              <TableCell>
                {{ getAvgScore(i.evaluations) }}
              </TableCell>
              <TableCell>
                {{ formatDate(i.date) }}
              </TableCell>
              <TableCell class="text-right">
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
                      {{ t('interview.table.actionsPopover.view') }}
                    </button>
                    <button
                      v-if="i.status !== 'completed'"
                      @click="openEditDialog(i)"
                      class="w-full rounded px-3 py-2 text-left hover:bg-gray-100"
                    >
                      {{ t('interview.table.actionsPopover.edit') }}
                    </button>
                    <button
                      v-if="i.status !== 'completed'"
                      @click="openDeleteDialog(i)"
                      class="w-full rounded px-3 py-2 text-left text-red-600 hover:bg-red-100"
                    >
                      {{ t('interview.table.actionsPopover.delete') }}
                    </button>
                  </PopoverContent>
                </Popover>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

        <div
          v-if="Math.ceil(filteredInterviews.length / pageSize) > 1"
          class="flex justify-center pt-4"
        >
          <Pagination
            v-model:page="currentPage"
            :items-per-page="pageSize"
            :total="filteredInterviews.length"
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

      <div v-else class="flex h-96 flex-col items-center justify-center">
        <h2 class="text-3xl font-bold">{{ t('interview.noItems') }}</h2>
        <p class="text-xl text-gray-500">{{ t('interview.noItemsDescription') }}</p>
      </div>
    </div>

    <StartInterviewDialog v-model:open="isStartDialogOpen" :interviews="props.interviews" />
    <ViewInterviewDialog
      v-if="selectedInterview"
      v-model:open="isViewDialogOpen"
      :interview="selectedInterview"
    />
    <EditInterviewDialog
      v-if="selectedInterview"
      v-model:open="isEditDialogOpen"
      :interview="selectedInterview"
      :templates="props.templates"
    />
    <DeleteInterviewDialog
      v-if="selectedInterview"
      v-model:open="isDeleteDialogOpen"
      :interview="selectedInterview"
    />
  </AppLayout>
</template>
