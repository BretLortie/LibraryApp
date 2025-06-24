<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Book,
    Bookmark,
    BookOpen,
    Folder,
    LayoutGrid,
    Pencil,
    PlusCircle,
    RotateCcw,
    ShoppingCart,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

interface PageProps {
    auth: {
        user: {
            id: number
            name: string
            email: string
            avatar: string | null
            role: string
        } | null
    }
    [key: string]: unknown; // Add index signature to satisfy Inertia's PageProps constraint
}

const user = (usePage<PageProps>().props.auth.user);
const role = user?.role?.toLowerCase();
console.log('ROLE:', role);

const mainNavItems = computed(() => {
    const items: NavItem[] = [
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
    ];

    if (role === 'librarian' || role === 'admin') {
        items.push(
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
                title: 'Return Books',
                href: '/books/return',
                icon: RotateCcw,
            }
        );
    }

    if (role === 'customer' || role === 'admin') {
        items.push(
            {
                title: 'Book Checkout',
                href: '/books/checkout',
                icon: ShoppingCart,
            },

            {
                title: 'Review Books',
                href: '/books/review',
                icon: Pencil,
            },
        );

    }

    return items;
});

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
