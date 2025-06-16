<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
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

// Single filter input
const filter = ref('');

// Filtered books based on single filter (search title or author)
const filteredBooks = computed(() =>
  props.books.filter(book =>
    (book.title + ' ' + book.author)
      .toLowerCase()
      .includes(filter.value.toLowerCase())
  )
);

// Define the form data type
type BookFormData = {
  title: string;
  author: string;
  publisher: string;
  publicationDate: string;
  category: string;
  ISBN: string;
  pageCount: number;
};

// Store forms keyed by book id
const forms = ref<Record<number, ReturnType<typeof useForm<BookFormData>>>>(
  props.books.reduce((acc, book) => {
    acc[book.id] = useForm<BookFormData>({
      title: book.title,
      author: book.author,
      publisher: book.publisher,
      publicationDate: book.publicationDate,
      category: book.category,
      ISBN: book.ISBN,
      pageCount: book.pageCount,
    });
    return acc;
  }, {} as Record<number, ReturnType<typeof useForm<BookFormData>>>)
);

const showDeleteModal = ref(false);
const deleteBookId = ref<number | null>(null);

function submit(bookId: number) {
  forms.value[bookId].put(route('books.update', bookId));
}

function confirmDelete(bookId: number) {
  deleteBookId.value = bookId;
  showDeleteModal.value = true;
}

function cancelDelete() {
  showDeleteModal.value = false;
  deleteBookId.value = null;
}

function deleteBook() {
  if (!deleteBookId.value) return;
  Inertia.delete(route('books.destroy', deleteBookId.value), {
    onSuccess: () => {
      showDeleteModal.value = false;
      deleteBookId.value = null;
      Inertia.visit(route('books.index'));
    },
  });
}
</script>

<template>
  <Head title="Edit Books" />
  <AppLayout>
    <div
      class="w-full min-h-screen p-6 bg-white dark:bg-black rounded-2xl shadow-md"
      style="box-sizing: border-box;"
    >

        <h1 class="text-2xl font-bold mb-4 sm:mb-0">Edit Books</h1>
        <br>
        
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:gap-6">
        
        <input
          v-model="filter"
          type="text"
          placeholder="Search by title or author"
          class="p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 flex-grow max-w-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- Books grid: 2 columns filling full width -->
      <div class="grid grid-cols-2 gap-6 w-full">
        <div
          v-for="book in filteredBooks"
          :key="book.id"
          class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow"
        >
          <form @submit.prevent="submit(book.id)">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-medium mb-1">Title</label>
                <input
                  v-model="forms[book.id].title"
                  type="text"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block font-medium mb-1">Author</label>
                <input
                  v-model="forms[book.id].author"
                  type="text"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block font-medium mb-1">Publisher</label>
                <input
                  v-model="forms[book.id].publisher"
                  type="text"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block font-medium mb-1">Publication Date</label>
                <input
                  v-model="forms[book.id].publicationDate"
                  type="date"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block font-medium mb-1">Category</label>
                <input
                  v-model="forms[book.id].category"
                  type="text"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block font-medium mb-1">ISBN</label>
                <input
                  v-model="forms[book.id].ISBN"
                  type="text"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block font-medium mb-1">Page Count</label>
                <input
                  v-model="forms[book.id].pageCount"
                  type="number"
                  class="w-full p-2 border border-gray-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div class="col-span-2 flex space-x-4 mt-4">
                <button
                  type="submit"
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                  Save
                </button>
                <button
                  type="button"
                  @click="confirmDelete(book.id)"
                  class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                >
                  Delete
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete confirmation modal -->
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full shadow-lg">
          <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
          <p>Are you sure you want to delete this book? This action cannot be undone.</p>
          <div class="mt-6 flex justify-end space-x-4">
            <button
              @click="cancelDelete"
              class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100"
            >
              Cancel
            </button>
            <button
              @click="deleteBook"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
