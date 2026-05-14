<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    colors: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search || '')
const searchTimeout = ref(null)

const paginationLinks = computed(() => props.colors?.links || [])

const applyFilters = () => {
    const params = {}

    if (search.value) params.search = search.value

    router.get(route('admin.color.index'), params, {
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
    applyFilters()
}

const editColor = (color) => {
    router.get(route('admin.color.edit', color.id))
}

const deleteColor = (color) => {
    if (confirm(`Вы уверены, что хотите удалить цвет "${color.name}"?`)) {
        router.delete(route('admin.color.destroy', color.id), {
            preserveScroll: true
        })
    }
}

watch(() => props.filters, (newFilters) => {
    search.value = newFilters.search || ''
}, { deep: true, immediate: true })
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление цветами</h3>
                    <Link :href="route('admin.color.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить цвет
                    </Link>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <input type="text" v-model="search" @input="onSearchInput" placeholder="Поиск по названию..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <button @click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Сбросить
                    </button>
                </div>

                <div v-if="colors?.data?.length" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Цвет</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Код</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="color in colors.data" :key="color.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ color.id }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800">{{ color.name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="w-8 h-8 rounded-full border border-gray-300"
                                        :style="{ backgroundColor: color.code }"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ color.code }}</code>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-400">{{ color.slug }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editColor(color)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteColor(color)"
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
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <p class="text-gray-500 text-lg">Цвета не найдены</p>
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
