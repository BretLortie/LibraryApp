<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const props = defineProps<{
    books: Array<{
        library_id: number;
        book_id: number;
        title: string;
        author: string;
        checked_out_by: string;
        checked_out_at: string;
    }>;
}>();

const returnForm = useForm({
    library_id: null as number | null,
    book_id: null as number | null,
});

function returnBook(libraryId: number, bookId: number) {
    returnForm.library_id = libraryId;
    returnForm.book_id = bookId;

    returnForm.post(route('books.return'), {
        preserveScroll: true,
        onSuccess: () => {
            returnForm.reset();
            window.location.reload(); // Refresh to show updated list
        },
    });
}
</script>

<template>

    <Head title="Return Books" />
    <AppLayout>
        <div class="w-full min-h-screen p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h1 class="text-3xl font-bold mb-6">Return Books</h1>

            <div class="grid grid-cols-2 gap-6">
                <div v-for="book in props.books" :key="book.library_id"
                    class="bg-gray-100 dark:bg-gray-800 rounded p-4 shadow">
                    <h2 class="text-xl font-semibold">{{ book.title }}</h2>
                    <p class="text-gray-700 dark:text-gray-300">Author: {{ book.author }}</p>
                    <p class="text-gray-700 dark:text-gray-300">Checked out by: {{ book.checked_out_by }}</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Checked out at: {{ new Date(book.checked_out_at).toLocaleString() }}
                    </p>
                    <button @click="returnBook(book.library_id, book.book_id)"
                        class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Return Book
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
