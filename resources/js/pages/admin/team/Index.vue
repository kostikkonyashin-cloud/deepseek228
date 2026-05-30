<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import Pagination from '../../../components/other/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    teamMembers: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    }
});

const paginationLinks = ref(props.teamMembers.links || []);

const editMember = (member) => {
    router.get(route('admin.team.edit', member.id));
};

const deleteMember = (member) => {
    router.delete(route('admin.team.destroy', member.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление командой</h3>
                    <Link :href="route('admin.team.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить участника
                    </Link>
                </div>

                <div v-if="teamMembers.data && teamMembers.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">ID</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Фото</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Фамилия</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Имя</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Должность</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Описание</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-500">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="member in teamMembers.data" :key="member.id"
                                class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-500">{{ member.id }}</td>
                                <td class="py-3 px-4">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-full overflow-hidden flex items-center justify-center">
                                        <img v-if="member.image_path" :src="member.image_path" :alt="member.name"
                                            class="w-full h-full object-cover">
                                        <svg v-else class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">{{ member.surname || '—' }}</td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">{{ member.name || '—' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ member.position || '—' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-500 max-w-xs truncate">{{ member.description ||
                                    '—' }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editMember(member)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteMember(member)"
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
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Члены команды не найдены</p>
                    <p class="text-gray-400 text-sm mt-2">Добавьте первого участника команды</p>
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
