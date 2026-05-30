<script setup>
import { Link } from '@inertiajs/vue3';
import AppBaseLayout from '@/Layouts/AppBaseLayout.vue';
</script>

<template>
    <AppBaseLayout>
        <div class="flex items-center justify-center px-4 py-32">
            <div class="text-center max-w-2xl mx-auto">
                <!-- Изображение 404 -->
                <div class="mb-8">
                    <img v-if="false" :src="`${route('index')}/storage/images/404.jpg`" alt="404"
                        class="mx-auto max-w-full h-auto">
                    <div v-else class="text-center">
                        <h1 class="text-8xl font-bold text-gray-800 mb-4">404</h1>
                        <div class="w-32 h-1 bg-gray-300 mx-auto mb-8"></div>
                    </div>
                </div>

                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Страница не найдена</h2>

                <div class="flex gap-4 justify-center">
                    <Link :href="route('index')"
                        class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                        На главную
                    </Link>
                </div>
            </div>
        </div>
    </AppBaseLayout>
</template>
