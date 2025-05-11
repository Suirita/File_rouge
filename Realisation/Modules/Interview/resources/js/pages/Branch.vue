<script setup lang="ts">
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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Eye, FileDown, FileUp, Pencil, PlusCircle, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import CreateBranchDialog from '../components/CreateBranchDialog.vue';
import DeleteBranchDialog from '../components/DeleteBranchDialog.vue';
import EditBranchDialog from '../components/EditBranchDialog.vue';
import ImportBranchDialog from '../components/ImportBranchDialog.vue';
import ViewBranchDialog from '../components/ViewBranchDialog.vue';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('breadcrumbs.dashboard'),
        href: '/dashboard',
    },
    {
        title: t('breadcrumbs.branches'),
        href: '/dashboard/branch',
    },
];

const props = defineProps<{
    branches: { id: number; canModify: boolean; name: string; questions: { id: number; question: string }[] }[];
}>();

const selectedBranchId = ref<string | null>(null);

const filteredBranches = computed(() => {
    if (selectedBranchId.value === null || selectedBranchId.value === 'all') {
        return props.branches;
    }
    return props.branches.filter((branch) => branch.id === Number(selectedBranchId.value));
});

const pageSize = 5;
const currentPage = ref(1);

const paginatedBranches = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    return filteredBranches.value.slice(start, start + pageSize);
});

const exportUsers = () => {
    window.location.href = route('branch.export');
};

const isCreateBranchDialogOpen = ref(false);
const isImportBranchDialogOpen = ref(false);
const isViewBranchDialogOpen = ref(false);
const isEditBranchDialogOpen = ref(false);
const isDeleteBranchDialogOpen = ref(false);

function openCreateBranchDialog() {
    isCreateBranchDialogOpen.value = true;
}

function openImportBranchDialog() {
    isImportBranchDialogOpen.value = true;
}

const branch = ref<{ id: number; name: string; questions: { id: number; question: string }[] } | undefined>(undefined);

function openViewBranchDialog(branchParam: { id: number; name: string; questions: { id: number; question: string }[] }) {
    branch.value = branchParam;
    isViewBranchDialogOpen.value = true;
}

function openEditBranchDialog(branchParam: { id: number; name: string; questions: { id: number; question: string }[] }) {
    branch.value = branchParam;
    isEditBranchDialogOpen.value = true;
}

function openDeleteBranchDialog(branchParam: { id: number; name: string; questions: { id: number; question: string }[] }) {
    branch.value = branchParam;
    isDeleteBranchDialogOpen.value = true;
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4 flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-3xl font-bold">{{ t('branches.title') }}</h1>
            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    <Select v-model="selectedBranchId">
                        <SelectTrigger class="w-[280px]">
                            <SelectValue :placeholder="t('branches.filterPlaceholder')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">{{ t('branches.filterAll') }}</SelectItem>
                            <SelectItem v-for="branch in props.branches" :key="branch.id" :value="branch.id">
                                {{ branch.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="flex items-center gap-2">
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button @click="openImportBranchDialog" size="sm" variant="outline" class="h-8 gap-1">
                                    <FileDown class="h-3.5 w-3.5" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent class="bg-gray-50">
                                <p>{{ t('branches.import') }}</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button @click="exportUsers" size="sm" variant="outline" class="h-8 gap-1">
                                    <FileUp class="h-3.5 w-3.5" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent class="bg-gray-50">
                                <p>{{ t('branches.export') }}</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    <Button size="sm" class="h-8 gap-1" @click="openCreateBranchDialog">
                        <PlusCircle class="h-3.5 w-3.5" />
                        <span> {{ t('branches.add') }} </span>
                    </Button>
                </div>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>{{ t('branches.table.name') }}</TableHead>
                        <TableHead>{{ t('branches.table.questions') }}</TableHead>
                        <TableHead class="text-right">{{ t('branches.table.actions') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="branch in paginatedBranches" :key="branch.id">
                        <TableCell>{{ branch.name }}</TableCell>
                        <TableCell>
                            <ul v-for="question in branch.questions" :key="question.id">
                                <li>{{ question.question }}</li>
                            </ul>
                        </TableCell>
                        <TableCell class="mr-2 flex justify-end gap-2">
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            class="transform bg-blue-500 py-5 text-white transition-all duration-300 ease-in-out hover:scale-105 hover:bg-blue-400 hover:text-black"
                                            @click="openViewBranchDialog(branch)"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent class="bg-gray-50">
                                        <p>{{ t('branches.table.view') }}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            v-if="branch.canModify"
                                            size="sm"
                                            variant="outline"
                                            class="transform bg-green-500 py-5 text-white transition-all duration-300 ease-in-out hover:scale-105 hover:bg-green-400 hover:text-black"
                                            @click="openEditBranchDialog(branch)"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent class="bg-gray-50">
                                        <p>{{ t('branches.table.edit') }}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            v-if="branch.canModify"
                                            size="sm"
                                            variant="outline"
                                            class="transform bg-red-500 py-5 text-white transition-all duration-300 ease-in-out hover:scale-105 hover:bg-red-400 hover:text-black"
                                            @click="openDeleteBranchDialog(branch)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent class="bg-gray-50">
                                        <p>{{ t('branches.table.delete') }}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <div v-if="Math.ceil(filteredBranches.length / pageSize) > 1" class="flex items-center justify-center pt-4">
                <Pagination v-model:page="currentPage" :items-per-page="pageSize" :total="filteredBranches.length" :sibling-count="1" show-edges>
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                        <PaginationFirst />
                        <PaginationPrev />

                        <template v-for="(item, index) in items" :key="index">
                            <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                                <Button class="h-10 w-10 p-0" :variant="item.value === currentPage ? 'default' : 'outline'">
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
        <CreateBranchDialog v-model:open="isCreateBranchDialogOpen" />
        <ImportBranchDialog v-model:open="isImportBranchDialogOpen" />
        <ViewBranchDialog v-model:open="isViewBranchDialogOpen" :branch="branch" />
        <EditBranchDialog v-model:open="isEditBranchDialogOpen" :branch="branch" />
        <DeleteBranchDialog v-model:open="isDeleteBranchDialogOpen" :branch="branch" />
    </AppLayout>
</template>
