<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const form = useForm({
  title: '',
  author: '',
  publisher: '',
  publicationDate: '',
  category: '',
  ISBN: '',
  pageCount: '',
});

function submit() {
  form.post(route('books.store'));
}
</script>

<template>
  <Head title="Add New Book" />

  <AppLayout :breadcrumbs="[
    { title: 'All Books', href: route('books.index') },
    {
        title: 'Add Book',
        href: ''
    }
  ]">
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Add New Book</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block font-semibold mb-1" for="title">Title *</label>
          <input
            v-model="form.title"
            id="title"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.title }"
            required
          />
          <p v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="author">Author *</label>
          <input
            v-model="form.author"
            id="author"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.author }"
            required
          />
          <p v-if="form.errors.author" class="text-red-600 text-sm mt-1">{{ form.errors.author }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="publisher">Publisher</label>
          <input
            v-model="form.publisher"
            id="publisher"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.publisher }"
          />
          <p v-if="form.errors.publisher" class="text-red-600 text-sm mt-1">{{ form.errors.publisher }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="publicationDate">Publication Date</label>
          <input
            v-model="form.publicationDate"
            id="publicationDate"
            type="date"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.publicationDate }"
          />
          <p v-if="form.errors.publicationDate" class="text-red-600 text-sm mt-1">{{ form.errors.publicationDate }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="category">Category</label>
          <input
            v-model="form.category"
            id="category"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.category }"
          />
          <p v-if="form.errors.category" class="text-red-600 text-sm mt-1">{{ form.errors.category }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="ISBN">ISBN</label>
          <input
            v-model="form.ISBN"
            id="ISBN"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.ISBN }"
          />
          <p v-if="form.errors.ISBN" class="text-red-600 text-sm mt-1">{{ form.errors.ISBN }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1" for="pageCount">Page Count</label>
          <input
            v-model="form.pageCount"
            id="pageCount"
            type="number"
            min="1"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.pageCount }"
          />
          <p v-if="form.errors.pageCount" class="text-red-600 text-sm mt-1">{{ form.errors.pageCount }}</p>
        </div>

        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
          :disabled="form.processing"
        >
          Add Book
        </button>
      </form>
    </div>
  </AppLayout>
</template>
