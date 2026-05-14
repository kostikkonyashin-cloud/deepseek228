<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    brands: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search || '')
const statusFilter = ref(props.filters.status || '')
const searchTimeout = ref(null)

const paginationLinks = computed(() => props.brands?.links || [])

const applyFilters = () => {
    const params = {}

    if (search.value) params.search = search.value
    if (statusFilter.value) params.status = statusFilter.value

    router.get(route('admin.brand.index'), params, {
        preserveState: true,
        preserveScroll: true
    })
}

const onSearchInput = () => {
    if (searchTimeout.value) clearTimeout(searchTimeout.value)
    searchTimeout.value = setTimeout(() => {
        applyFilters()
    }, 500)
}

const resetFilters = () => {
    search.value = ''
    statusFilter.value = ''
    applyFilters()
}

const editBrand = (brand) => {
    router.get(route('admin.brand.edit', brand.id))
}

const deleteBrand = (brand) => {
    if (confirm(`Вы уверены, что хотите удалить бренд "${brand.name}"?`)) {
        router.delete(route('admin.brand.destroy', brand.id), {
            preserveScroll: true
        })
    }
}

const toggleActive = (brand) => {
    router.post(route('admin.brand.toggle-active', brand.id), {}, {
        preserveScroll: true
    })
}

const getStatusBadgeClass = (isActive) => {
    return isActive
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800'
}

watch(() => props.filters, (newFilters) => {
    search.value = newFilters.search || ''
    statusFilter.value = newFilters.status || ''
}, { deep: true, immediate: true })
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление брендами</h3>
                    <Link :href="route('admin.brand.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить бренд
                    </Link>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <input type="text" v-model="search" @input="onSearchInput" placeholder="Поиск по названию..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="w-full md:w-40">
                        <select v-model="statusFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все статусы</option>
                            <option value="active">Активные</option>
                            <option value="inactive">Неактивные</option>
                        </select>
                    </div>
                    <button @click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Сбросить
                    </button>
                </div>

                <div v-if="brands?.data?.length" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Логотип</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="brand in brands.data" :key="brand.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ brand.id }}</td>
                                <td class="px-4 py-3">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                        <img v-if="brand.image_path" :src="brand.image_path" :alt="brand.name"
                                            class="w-full h-full object-cover">
                                        <svg v-else class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 10h-3v3c0 .6-.4 1-1 1s-1-.4-1-1v-3h-3c-.6 0-1-.4-1-1s.4-1 1-1h3V9c0-.6.4-1 1-1s1 .4 1 1v3h3c.6 0 1 .4 1 1s-.4 1-1 1z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800">{{ brand.name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-400">{{ brand.slug }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusBadgeClass(brand.is_active)]">
                                        {{ brand.is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editBrand(brand)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="toggleActive(brand)"
                                            :class="brand.is_active ? 'text-yellow-500 hover:text-yellow-600' : 'text-green-500 hover:text-green-600'"
                                            :title="brand.is_active ? 'Деактивировать' : 'Активировать'">
                                            <svg v-if="brand.is_active" class="w-5 h-5" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteBrand(brand)"
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
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 10h-3v3c0 .6-.4 1-1 1s-1-.4-1-1v-3h-3c-.6 0-1-.4-1-1s.4-1 1-1h3V9c0-.6.4-1 1-1s1 .4 1 1v3h3c.6 0 1 .4 1 1s-.4 1-1 1z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Бренды не найдены</p>
                    <p class="text-gray-400 text-sm mt-2">Попробуйте изменить параметры поиска</p>
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
