<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Books', href: '' },
];

interface Book {
  id: number;
  title: string;
  author: string;
}

const props = defineProps<{
  books: Book[];
}>();

// Now you can use `props.books` in your template or create a local alias:
const books = props.books;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Books</h1>
            <!-- Replace below with actual book list component or markup -->
            <ul class="space-y-2">
                <li v-for="book in books" :key="book.id" class="p-4 bg-muted rounded-2xl shadow-sm border border-border">
                    <h2 class="font-semibold">{{ book.title }}</h2>
                    <p class="text-sm text-gray-600">By {{ book.author }}</p>
                </li>
            </ul>

        </div>
    </AppLayout>
</template>
