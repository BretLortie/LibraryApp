<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const props = defineProps<{
    books: {
        availability: number,
        book: {
            title: string,
            author: string,
            description: string,
            coverImage: string,
            avgRating: number | null
        }
    }[]
}>();

const sortBy = ref('');
const sortDir = ref<'asc' | 'desc'>('asc');
const availabilityFilter = ref('');

const applyFilters = () => {
    router.get('/featured-books', {  // <-- fixed route to match your web.php
        sort_by: sortBy.value || undefined,
        direction: sortDir.value || undefined,
        availability: availabilityFilter.value === '' ? undefined : availabilityFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-4 bg-gray-100 dark:bg-black min-h-screen">
            <h1 class="text-2xl font-bold">Featured Books</h1>

            <!-- Filter + Sort Controls -->
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium mb-1">Sort by</label>
                    <select v-model="sortBy" @change="applyFilters" class="border rounded p-2 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Random</option>
                        <option value="title">Title</option>
                        <option value="author">Author</option>
                        <option value="avgRating">Rating</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Direction</label>
                    <select v-model="sortDir" @change="applyFilters" class="border rounded p-2 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Availability</label>
                    <select v-model="availabilityFilter" @change="applyFilters" class="border rounded p-2 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100">>
                        <option value="">All</option>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
            </div>

            <!-- Book Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                <div v-for="(entry, index) in props.books" :key="index"
                    class="rounded-lg border border-gray-200 shadow-sm p-4 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <img :src="entry.book.coverImage" alt="Cover" class="w-full h-48 object-cover rounded" />
                    <h2 class="text-lg font-semibold mt-2">{{ entry.book.title }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">By {{ entry.book.author }}</p>
                    <p class="text-sm mt-1 line-clamp-3">{{ entry.book.description }}</p>
                    <div class="mt-2 text-sm">
                        <strong>Average Rating:</strong> {{ entry.book.avgRating ?? 'N/A' }}
                    </div>
                    <div class="mt-1 text-sm">
                        <strong>Availability: </strong>
                        <span :class="entry.availability ? 'text-green-500' : 'text-red-500'">
                            {{ entry.availability ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>