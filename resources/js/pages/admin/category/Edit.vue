<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    category: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.category.name,
    slug: props.category.slug,
    description: props.category.description || '',
    icon_path: props.category.icon_path || '',
    is_active: props.category.is_active ?? true
})

const submit = () => {
    form.put(route('admin.category.update', props.category.id), {
        onSuccess: () => {
            // Flash сообщение уже придет с бэка
        }
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
                    <h3 class="text-xl font-semibold text-gray-800">Редактирование категории</h3>
                    <Link :href="route('admin.category.index')"
                        class="text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Назад к списку
                    </Link>
                </div>

                <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                    <!-- Название -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.name }">
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.slug" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.slug }">
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Путь к иконке -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Путь к иконке
                        </label>
                        <input type="text" v-model="form.icon_path"
                            placeholder="/storage/images/categories/category.png"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.icon_path }">
                        <p class="text-xs text-gray-400 mt-1">Пример: /storage/images/categories/category.png</p>
                        <p v-if="form.errors.icon_path" class="mt-1 text-sm text-red-500">{{ form.errors.icon_path }}
                        </p>
                    </div>

                    <!-- Текущая иконка (если есть) -->
                    <div v-if="category.icon_path" class="bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Текущая иконка</label>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center p-2">
                                <img :src="`${route('index')}/storage/${category.icon_path}.png`" :alt="category.name"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                            <span class="text-xs text-gray-500 break-all">{{ category.icon_path }}</span>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Описание
                        </label>
                        <textarea v-model="form.description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.description }"></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">{{ form.errors.description
                        }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" :checked="form.is_active === 1 || form.is_active === true"
                            @change="form.is_active = $event.target.checked" id="is_active"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <label for="is_active" class="text-sm text-gray-700 cursor-pointer">
                            Подкатегория активна
                        </label>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Сохранить изменения' }}
                        </button>
                        <Link :href="route('admin.category.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                            Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </template>
    </ProfileLayout>
</template>
