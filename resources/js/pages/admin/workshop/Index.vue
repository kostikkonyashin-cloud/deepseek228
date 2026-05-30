<!-- resources/js/Pages/Admin/Workshop/Index.vue -->
<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import Pagination from '../../../components/other/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    services: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    }
});

const paginationLinks = ref(props.services.links || []);

const editService = (service) => {
    router.get(route('admin.workshop.edit', service.id));
};

const deleteService = (service) => {
    router.delete(route('admin.workshop.destroy', service.id), {
        preserveScroll: true,
    });
};

const formatPrice = (price) => {
    if (!price) return '—';
    return price.toLocaleString() + ' ₽';
};
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Услуги мастерской</h3>
                    <Link :href="route('admin.workshop.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить услугу
                    </Link>
                </div>

                <div v-if="services.data && services.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">ID</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Изображение</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Название</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Описание</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Цена</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-500">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="service in services.data" :key="service.id"
                                class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-500">{{ service.id }}</td>
                                <td class="py-3 px-4">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                        <img v-if="service.image_path" :src="service.image_path" :alt="service.title"
                                            class="w-full h-full object-cover">
                                        <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">{{ service.title }}</td>
                                <td class="py-3 px-4 text-sm text-gray-500 max-w-xs truncate">{{ service.description }}
                                </td>
                                <td class="py-3 px-4 text-sm font-semibold text-gray-700">{{ formatPrice(service.price)
                                }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editService(service)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteService(service)"
                                            class="text-red-500 hover:text-red-600 transition" title="Удалить">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-4H7v4" />
                    </svg>
                    <p class="text-gray-500 text-lg">Услуги не найдены</p>
                    <p class="text-gray-400 text-sm mt-2">Добавьте первую услугу мастерской</p>
                </div>

                <div v-if="paginationLinks.length > 3" class="mt-6 flex justify-center">
                    <div class="flex gap-1">
                        <Link v-for="link in paginationLinks" :key="link.label" :href="link.url || '#'" :class="[
                            'px-3 py-2 rounded-lg text-sm transition',
                            link.active ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'
                        ]" v-html="link.label" />
                    </div>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
