<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

// Get the reactive locale property from Vue I18n
const { locale, t } = useI18n();

// Function to update the language
function updateLanguage(newLang: string) {
    locale.value = newLang;
}

// Appearance tabs remain unchanged
const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
    { value: 'system', Icon: Monitor, label: 'System' },
] as const;

// New language tabs - update/add as needed
const languageTabs = [
    { value: 'en', label: 'English' },
    { value: 'fr', label: 'Français' },
] as const;
</script>

<template>
    <!-- Appearance toggle buttons -->
    <div :class="['inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', containerClass]">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-white shadow-sm dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>

    <!-- Language selection buttons -->
    <div :class="['mt-4 flex max-w-fit gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', containerClass]">
        <button
            v-for="{ value, label } in languageTabs"
            :key="value"
            @click="updateLanguage(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                locale === value
                    ? 'bg-white shadow-sm dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
        >
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
