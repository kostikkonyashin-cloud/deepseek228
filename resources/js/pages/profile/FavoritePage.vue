<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import Pagination from '../../components/other/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    favorites: Object
});

const removeFromFavorites = (productId) => {
    router.delete(route('favorites.destroy', productId), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Товар удален из избранного');
        }
    });
};
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm">
                <div class="border-b border-gray-100 p-6">
                    <h1 class="text-2xl font-bold text-gray-900">Избранное</h1>
                    <p class="text-gray-500 mt-1">Товары, которые вы добавили в избранное</p>
                </div>

                <!-- Если нет избранных товаров -->
                <div v-if="!favorites.data || favorites.data.length === 0" class="text-center py-16">
                    <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg mb-4">В избранном пока пусто</p>
                    <Link :href="route('product.index', { category: 'all' })"
                        class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                        Перейти в каталог
                    </Link>
                </div>

                <!-- Таблица с избранными товарами -->
                <div v-else class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600 w-[45%]">Товар</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600 w-[20%]">Цена</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600 w-[15%]">Наличие</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600 w-[20%]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in favorites.data" :key="product.id"
                                class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                                <!-- Информация о товаре -->
                                <td class="py-4 px-6">
                                    <div class="flex gap-4 items-center">
                                        <Link
                                            :href="route('product.show', { category: product.category?.slug, product: product.slug })"
                                            class="shrink-0">
                                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                                <img v-if="product.product_images && product.product_images[0]"
                                                    :src="product.product_images[0].image_path" :alt="product.name"
                                                    class="w-full h-full object-cover">
                                                <div v-else class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </Link>
                                        <div class="flex flex-col">
                                            <Link
                                                :href="route('product.show', { category: product.category?.slug, product: product.slug })"
                                                class="font-semibold text-gray-900 hover:text-purple-600 transition line-clamp-1">
                                                {{ product.brand?.name }} {{ product.name }}
                                            </Link>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ product.description }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Цена -->
                                <td class="py-4 px-6 align-middle">
                                    <div>
                                        <span class="font-bold text-lg text-gray-900">
                                            {{ Math.round(product.price * (1 - (product.discount || 0) /
                                                100)).toLocaleString() }} ₽
                                        </span>
                                        <span v-if="product.discount" class="text-sm text-gray-400 line-through ml-2">
                                            {{ product.price.toLocaleString() }} ₽
                                        </span>
                                        <span v-if="product.discount"
                                            class="ml-2 inline-block bg-red-100 text-red-600 text-xs font-semibold px-2 py-0.5 rounded">
                                            -{{ product.discount }}%
                                        </span>
                                    </div>
                                </td>

                                <!-- Наличие -->
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium"
                                            :class="product.is_available ? 'text-green-600' : 'text-red-600'">
                                            {{ product.is_available ? 'В наличии' : 'Нет в наличии' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Действия -->
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex gap-3">
                                        <button @click="removeFromFavorites(product.id)"
                                            class="p-2 text-gray-400 hover:text-red-500 transition cursor-pointer"
                                            title="Удалить из избранного">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <Link
                                            :href="route('product.show', { category: product.category?.slug, product: product.slug })"
                                            class="p-2 text-gray-400 hover:text-purple-600 transition cursor-pointer"
                                            title="Перейти к товару">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="favorites.meta && favorites.meta.last_page > 1" class="border-t border-gray-100 p-6">
                    <Pagination :links="favorites.links" />
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
