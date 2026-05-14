<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const user = computed(() => {
    return page.props.user_info;
});

const logout = () => {
    router.delete(route('profile.destroy'), {
        onSuccess: (message) => {
            console.log(message)
        },
        onError: (message) => {
            console.log(message)
        }
    });
}
</script>
<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h2 class="text-xl font-bold text-gray-800">{{ user.login }}</h2>
                <p class="text-gray-500">{{ user.email }}</p>
            </div>
            <div class="flex gap-3">
                <form @submit.prevent="logout">
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition cursor-pointer">
                        Выйти
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
