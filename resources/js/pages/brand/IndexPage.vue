<script setup>
defineProps(['brands']);
import AppBaseLayout from '../../layouts/AppBaseLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Бренды</h1>
            <div v-if="brands && brands.length"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                <Link :href="route('brand.show', { brand: brand.slug })"
                    class="flex flex-col gap-2 hover:opacity-80 transition group" v-for="brand in brands"
                    :key="brand.id">
                    <div
                        class="bg-white border border-gray-200 rounded-lg aspect-square w-full flex items-center justify-center overflow-hidden p-4 group-hover:border-purple-500 transition">
                        <img v-if="brand.image_path" :src="brand.image_path" :alt="brand.name"
                            class="w-full h-full object-contain">
                        <svg v-else class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 10h-3v3c0 .6-.4 1-1 1s-1-.4-1-1v-3h-3c-.6 0-1-.4-1-1s.4-1 1-1h3V9c0-.6.4-1 1-1s1 .4 1 1v3h3c.6 0 1 .4 1 1s-.4 1-1 1z" />
                        </svg>
                    </div>
                    <span class="text-sm text-center text-gray-700 line-clamp-2 group-hover:text-purple-600 transition">
                        {{ brand.name }}
                    </span>
                </Link>
            </div>

            <div v-else class="text-center py-12">
                <p class="text-gray-500">Бренды не найдены</p>
            </div>
        </div>
    </AppBaseLayout>
</template>
