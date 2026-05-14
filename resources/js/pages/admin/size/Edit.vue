<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    size: {
        type: Object,
        required: true
    },
    sizeTypes: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    name: props.size.name,
    slug: props.size.slug,
    size_type_id: props.size.size_type_id || null,
    is_active: props.size.is_active ?? true
})

const submit = () => {
    form.put(route('admin.size.update', props.size.id), {
        onSuccess: () => { }
    })
}

const generateSlug = () => {
    if (form.name) {
        form.slug = form.name
            .toLowerCase()
            .replace(/[^a-zа-яё0-9\s]/gi, '')
            .replace(/\s+/g, '-')
            .replace(/[^\w-]+/g, '')
    }
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Редактирование размера</h3>
                    <Link :href="route('admin.size.index')"
                        class="text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Назад к списку
                    </Link>
                </div>

                <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.name" @blur="generateSlug" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.name }">
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.slug" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.slug }">
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип размера</label>
                        <select v-model="form.size_type_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.size_type_id }">
                            <option :value="null">Не выбрано</option>
                            <option v-for="type in sizeTypes" :key="type.id" :value="type.id">
                                {{ type.name }} ({{ type.category?.name || 'Без категории' }})
                            </option>
                        </select>
                        <p v-if="form.errors.size_type_id" class="mt-1 text-sm text-red-500">{{ form.errors.size_type_id
                            }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" v-model="form.is_active" id="is_active"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <label for="is_active" class="text-sm text-gray-700 cursor-pointer">
                            Размер активен
                        </label>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-500">
                        <div class="flex justify-between mb-2">
                            <span>Дата создания:</span>
                            <span>{{ size.created_at }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Последнее обновление:</span>
                            <span>{{ size.updated_at }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Сохранить изменения' }}
                        </button>
                        <Link :href="route('admin.size.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                            Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </template>
    </ProfileLayout>
</template>
