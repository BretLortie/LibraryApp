<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    book: { id: number; title: string; author: string };
    errors: { review?: string[]; rating?: string[] };
    flash: { success?: string };
}>();

const reviewText = ref('');
const rating = ref(0);
const successMessage = ref(props.flash.success || '');

const stars = computed(() => {
    return [1, 2, 3, 4, 5].map((star) => ({
        filled: star <= rating.value,
    }));
});

function setRating(value: number) {
    rating.value = value;
}

function submitReview() {
    router.post(
        route('books.review.submit', { book: props.book.id }),
        { review: reviewText.value, rating: rating.value },
        {
            onSuccess: () => {
                successMessage.value = 'Review submitted successfully.';
                reviewText.value = '';
                rating.value = 0;
            },
        }
    );
}
</script>

<template>

    <Head :title="`Review: ${props.book.title}`" />
    <AppLayout>
        <div class="w-full p-6 bg-white dark:bg-gray-900 min-h-screen rounded-2xl shadow max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Review "{{ props.book.title }}"</h1>
            <p class="mb-4 text-gray-700 dark:text-gray-300"><strong>Author:</strong> {{ props.book.author }}</p>

            <div class="mb-4">
                <label class="block mb-1 font-semibold dark:text-gray-200">Rating</label>
                <div class="flex space-x-1 text-3xl cursor-pointer select-none">
                    <span v-for="(star, index) in stars" :key="index" @click="setRating(index + 1)"
                        :class="star.filled ? 'text-yellow-400' : 'text-gray-400 dark:text-gray-600'">
                        ★
                    </span>
                </div>
                <p v-if="props.errors.rating" class="text-red-600 mt-1">{{ props.errors.rating[0] }}</p>
            </div>

            <textarea v-model="reviewText" rows="6" placeholder="Write your review here..."
                class="w-full p-3 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 mb-2"></textarea>

            <p v-if="props.errors.review" class="text-red-600 mb-4">{{ props.errors.review[0] }}</p>
            <p v-if="successMessage" class="text-green-600 mb-4">{{ successMessage }}</p>

            <button @click="submitReview" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                Submit Review
            </button>
        </div>
    </AppLayout>
</template>
