<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
  books: Array<{
    id: number;
    title: string;
    author: string;
    publisher: string;
    publicationDate: string;
    category: string;
    ISBN: string;
    pageCount: number;
  }>;
}>();

const search = ref('');

const filteredBooks = computed(() =>
  props.books.filter(book =>
    book.title.toLowerCase().includes(search.value.toLowerCase()) ||
    book.author.toLowerCase().includes(search.value.toLowerCase())
  )
);

type Book = {
  id: number;
  title: string;
  author: string;
  publisher: string;
  publicationDate: string;
  category: string;
  ISBN: string;
  pageCount: number;
};

const forms = ref<Record<number, ReturnType<typeof useForm<Book>>>>(
  props.books.reduce((acc, book) => {
    acc[book.id] = useForm<Book>({ ...book });
    return acc;
  }, {} as Record<number, ReturnType<typeof useForm<Book>>>)
);

function updateBook(id: number) {
  forms.value[id].put(route('books.update', id));
}
</script>

<template>
  <Head title="Edit Books" />
  <AppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Books</h1>

      <input
        v-model="search"
        type="text"
        placeholder="Search by title or author..."
        class="mb-4 p-2 w-full border border-gray-300 rounded"
      />

      <div v-for="book in filteredBooks" :key="book.id" class="p-4 mb-4 bg-white dark:bg-gray-900 rounded shadow">
        <form @submit.prevent="updateBook(book.id)" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block font-medium">Title</label>
            <input v-model="forms[book.id].title" type="text" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">Author</label>
            <input v-model="forms[book.id].author" type="text" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">Publisher</label>
            <input v-model="forms[book.id].publisher" type="text" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">Publication Date</label>
            <input v-model="forms[book.id].publicationDate" type="date" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">Category</label>
            <input v-model="forms[book.id].category" type="text" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">ISBN</label>
            <input v-model="forms[book.id].ISBN" type="text" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block font-medium">Page Count</label>
            <input v-model="forms[book.id].pageCount" type="number" class="w-full p-2 border rounded" />
          </div>
          <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
