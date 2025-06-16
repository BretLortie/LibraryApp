<script setup lang="ts">
import { onMounted } from 'vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const props = defineProps<{
    books: {
        availability: string,
        book: {
            title: string,
            author: string,
            description: string,
            coverImage: string,
            avgRating: number | null
        }
    }[]
}>();

onMounted(() => {
    props.books.forEach((entry, i) => {
        console.log(`Book #${i + 1} coverImage URL:`, entry.book.coverImage);
    });
});
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Featured Books</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="(entry, index) in books"
                    :key="index"
                    class="rounded-lg border shadow-sm p-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                >
                    <img :src="entry.book.coverImage" alt="Cover" class="w-full h-48 object-cover rounded" />
                    <h2 class="text-lg font-semibold mt-2">{{ entry.book.title }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">By {{ entry.book.author }}</p>
                    <p class="text-sm mt-1 line-clamp-3">{{ entry.book.description }}</p>
                    <div class="mt-2 text-sm">
                        <strong>Average Rating:</strong> {{ entry.book.avgRating ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
