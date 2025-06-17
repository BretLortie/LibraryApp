<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

interface Book {
  id: number;
  title: string;
  author: string;
  description: string;
}

const props = defineProps<{
  books: Book[];
  filters: { search: string };
}>();

const filters = ref({
  search: props.filters.search || '',
});

watch(filters, () => {
  searchBooks();
});

function searchBooks() {
  Inertia.get(
    route('books.review.landing'),
    { search: filters.value.search },
    { preserveState: true, replace: true }
  );
}

function goToReview(bookId: number) {
  router.visit(route('books.review.form', { book: bookId }));
}

const successMessage = ref('');
</script>

<template>

  <Head title="Review Books" />
  <AppLayout>
    <div class="w-full p-6 bg-white dark:bg-gray-900 min-h-screen rounded-2xl shadow">
      <h1 class="text-3xl font-bold mb-6">Review Books</h1>

      <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow mb-6">
        <input v-model="filters.search" type="text" placeholder="Search by title or author..."
          class="search-input w-full" />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="book in props.books" :key="book.id" class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-2">{{ book.title }}</h2>
          <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">
            <strong>Author:</strong> {{ book.author }}
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ book.description }}
          </p>
          <button @click="goToReview(book.id)" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Leave Review
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
