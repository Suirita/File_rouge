import { createContext } from 'radix-vue';
import type { ComputedRef, Ref } from 'vue';

export const SIDEBAR_COOKIE_NAME = 'sidebar:state';
export const SIDEBAR_COOKIE_MAX_AGE = 60 * 60 * 24 * 7;
export const SIDEBAR_WIDTH = '14rem';
export const SIDEBAR_WIDTH_MOBILE = '18rem';
export const SIDEBAR_WIDTH_ICON = '4rem';
export const SIDEBAR_KEYBOARD_SHORTCUT = { ctrl: true, key: 'b' };

export const [useSidebar, provideSidebarContext] = createContext<{
    state: ComputedRef<'expanded' | 'collapsed'>;
    open: Ref<boolean>;
    setOpen: (value: boolean) => void;
    isMobile: Ref<boolean>;
    openMobile: Ref<boolean>;
    setOpenMobile: (value: boolean) => void;
    toggleSidebar: () => void;
}>('Sidebar');
