<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Book, Bookmark, BookOpen, Folder, LayoutGrid, Pencil, PlusCircle, RotateCcw, ShoppingCart } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'All Books',
        href: '/books',
        icon: Book,
    },
    {
        title: 'Featured Books',
        href: '/featured-books',
        icon: Bookmark,
    },
    {
        title: 'Add Book',
        href: '/books/create',
        icon: PlusCircle,
    },
    {
        title: 'Edit Books',
        href: '/books/edit',
        icon: BookOpen,
    },
    {
        title: 'Book Checkout',
        href: '/books/checkout',
        icon: ShoppingCart,
    },
    {
        title: 'Return Books',
        href: '/books/return',
        icon: RotateCcw,
    },
    {
        title: 'Review Books',
        href: '/books/review',
        icon: Pencil,
    }



];


const footerNavItems: NavItem[] = [
    {
        title: 'This Projects Repo',
        href: 'https://github.com/BretLortie/LibraryApp',
        icon: Folder,
    },
    {
        title: 'Laravel Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Laravel Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
