<script setup lang="ts">
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'All Books', href: '' },
];

interface Review {
    id: number;
    user: {
        name: string;
        email?: string;
    };
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
const searchTerm = ref('');

const filteredBooks = computed(() => {
    return books.filter(book =>
        book.title.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="Book Database" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Book Database</h1>
            <h2>Click a book for more details</h2>
            <br>

            <input
                v-model="searchTerm"
                type="text"
                placeholder="Search by title..."
                class="mb-6 p-2 border border-border rounded w-full"
            />

            <ul class="space-y-2">
                <li v-for="book in filteredBooks" :key="book.id"
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
                            <ul class="mt-2 space-y-3">
                                <li v-for="review in book.reviews" :key="review.id"
                                    class="border border-border bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-gray-100">
                                        <strong>{{ review.user.name }}</strong>
                                        ({{ review.user.email ?? 'no email' }})
                                        rated it <strong>{{ review.rating }}/5</strong>
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2 italic">
                                        "{{ review.description }}"
                                    </p>
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
