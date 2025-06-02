<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Newspaper, FileQuestion } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';
import AppLogo from './AppLogo.vue';

const { t } = useI18n();

const mainNavItems: NavItem[] = [
    {
        title: t('sidebar.dashboard'),
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: t('sidebar.template'),
        href: '/dashboard/template',
        icon: Newspaper,
    },
    {
        title: t('sidebar.question'),
        href: '/dashboard/question',
        icon: FileQuestion,
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="md" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
