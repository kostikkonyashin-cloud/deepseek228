<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../../layouts/ProfileLayout.vue'

const props = defineProps({
    types: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    },
    categories: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search || '')
const categoryFilter = ref(props.filters.category || '')
const statusFilter = ref(props.filters.status || '')
const searchTimeout = ref(null)

const paginationLinks = computed(() => props.types?.links || [])

const applyFilters = () => {
    const params = {}

    if (search.value) params.search = search.value
    if (categoryFilter.value) params.category = categoryFilter.value
    if (statusFilter.value) params.status = statusFilter.value

    router.get(route('admin.category.types.index'), params, {
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
    categoryFilter.value = ''
    statusFilter.value = ''
    applyFilters()
}

const editType = (type) => {
    router.get(route('admin.category.types.edit', type.id))
}

const deleteType = (type) => {
    if (confirm(`Вы уверены, что хотите удалить подкатегорию "${type.name}"?`)) {
        router.delete(route('admin.category.types.destroy', type.id), {
            preserveScroll: true
        })
    }
}

const toggleActive = (type) => {
    router.post(route('admin.category.types.toggle-active', type.id), {}, {
        preserveScroll: true
    })
}

const getStatusBadgeClass = (isActive) => {
    return isActive
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800'
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление подкатегориями</h3>
                    <div class="flex gap-2">
                        <Link :href="route('admin.category.types.create')"
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Добавить подкатегорию
                        </Link>
                        <Link :href="route('admin.category.index')"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            К категориям
                        </Link>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <input type="text" v-model="search" @input="onSearchInput" placeholder="Поиск по названию..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="w-full md:w-48">
                        <select v-model="categoryFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все категории</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">
                                {{ cat.name }}
                            </option>
                        </select>
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

                <div v-if="types?.data?.length" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Категория
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата
                                    создания</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="type in types.data" :key="type.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ type.id }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800">{{ type.name }}</div>
                                    <div class="text-xs text-gray-400">{{ type.slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ type.category?.name || '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusBadgeClass(type.is_active)]">
                                        {{ type.is_active ? 'Активна' : 'Неактивна' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ type.created_at?.split(' ')[0] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editType(type)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="toggleActive(type)"
                                            :class="type.is_active ? 'text-yellow-500 hover:text-yellow-600' : 'text-green-500 hover:text-green-600'"
                                            :title="type.is_active ? 'Деактивировать' : 'Активировать'">
                                            <svg v-if="type.is_active" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteType(type)"
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
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-gray-500 text-lg">Подкатегории не найдены</p>
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
