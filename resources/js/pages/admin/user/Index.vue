<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    users: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    },
    roles: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search || '')
const roleFilter = ref(props.filters.role || '')
const searchTimeout = ref(null)

const paginationLinks = computed(() => props.users.links || [])

const applyFilters = () => {
    router.get(route('admin.user.index'), {
        search: search.value,
        role: roleFilter.value
    }, {
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
    roleFilter.value = ''
    applyFilters()
}

const editUser = (user) => {
    router.get(route('admin.user.edit', user.id))
}

const deleteUser = (user) => {
    if (confirm(`Вы уверены, что хотите удалить пользователя "${user.full_name}"?`)) {
        router.delete(route('admin.user.destroy', user.id), {
            preserveScroll: true,
        })
    }
}

const toggleBlock = (user) => {
    router.post(route('admin.user.toggle-block', user.id), {}, {
        preserveScroll: true
    })
}

const getRoleBadgeClass = (roleSlug) => {
    const classes = {
        admin: 'bg-red-100 text-red-800',
        manager: 'bg-blue-100 text-blue-800',
        client: 'bg-green-100 text-green-800'
    }
    return classes[roleSlug] || 'bg-gray-100 text-gray-800'
}

const getRoleName = (roleSlug) => {
    const names = {
        admin: 'Администратор',
        manager: 'Менеджер',
        client: 'Клиент'
    }
    return names[roleSlug] || roleSlug
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление пользователями</h3>
                    <Link :href="route('admin.user.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить пользователя
                    </Link>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <input type="text" v-model="search" @input="onSearchInput"
                            placeholder="Поиск по имени, email или логину..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="sm:w-48">
                        <select v-model="roleFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все роли</option>
                            <option v-for="role in roles" :key="role.id" :value="role.slug">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                    <button @click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Сбросить
                    </button>
                </div>

                <div v-if="users.data && users.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">ID</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">ФИО</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Логин</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Email</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Роль</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Статус</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Дата регистрации</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-500">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id" class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-500">{{ user.id }}</td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">{{ user.full_name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ user.login }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ user.email }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        :class="['px-2 py-1 rounded-full text-xs font-medium text-center', getRoleBadgeClass(user.role?.slug)]">
                                        {{ getRoleName(user.role?.name) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span :class="[
                                        'px-2 py-1 rounded-full text-xs font-medium',
                                        user.is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                                    ]">
                                        {{ user.is_blocked ? 'Заблокирован' : 'Активен' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500">{{ user.created_at?.split(' ')[0] }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editUser(user)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="toggleBlock(user)"
                                            :class="user.is_blocked ? 'text-green-500 hover:text-green-600' : 'text-yellow-500 hover:text-yellow-600'"
                                            :title="user.is_blocked ? 'Разблокировать' : 'Заблокировать'">
                                            <svg v-if="user.is_blocked" class="w-5 h-5" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </button>
                                        <button v-if="user.role?.slug !== 'admin'" @click="deleteUser(user)"
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
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Пользователи не найдены</p>
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
