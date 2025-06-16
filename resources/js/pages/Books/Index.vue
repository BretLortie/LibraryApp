<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Books', href: '' },
];

interface Review {
    id: number;
    user: string;
    rating: number;
    description: string;
}

interface Book {
    id: number;
    title: string;
    author: string;
    publisher: string;
    publicationDate: string;
    category: string;
    ISBN: string;
    pageCount: number;
    reviews: Review[];
}

const props = defineProps<{
    books: Book[];
}>();

const books = props.books;
const expandedBookId = ref<number | null>(null);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Books</h1>
            <ul class="space-y-2">
                <li v-for="book in books" :key="book.id"
                    class="p-4 bg-muted rounded-2xl shadow-sm border border-border mb-4 cursor-pointer"
                    @click="expandedBookId = expandedBookId === book.id ? null : book.id">
                    <h2 class="font-semibold">{{ book.title }}</h2>
                    <p class="text-sm text-gray-600">By {{ book.author }}</p>

                    <div v-if="expandedBookId === book.id"
                        class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <p><strong>Publisher:</strong> {{ book.publisher }}</p>
                        <p><strong>Publication Date:</strong> {{ book.publicationDate }}</p>
                        <p><strong>Category:</strong> {{ book.category }}</p>
                        <p><strong>ISBN:</strong> {{ book.ISBN }}</p>
                        <p><strong>Page Count:</strong> {{ book.pageCount }}</p>

                        <div v-if="book.reviews && book.reviews.length">
                            <strong>Customer Reviews:</strong>
                            <ul class="mt-2 space-y-1">
                                <li v-for="review in book.reviews" :key="review.id"
                                    class="border-l-4 pl-2 border-blue-500">
                                    <p><strong>{{ review.user }}</strong> rated {{ review.rating }}/5</p>
                                    <p>{{ review.description }}</p>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-sm text-gray-500 mt-2">
                            No customer reviews yet.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
