<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const form = useForm({
    name: '',
    icon_path: '',
    is_active: true
})

const submit = () => {
    form.post(route('admin.category.store'))
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Создание категории</h3>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.name }">
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

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

                    <div class="flex items-center gap-3">
                        <input type="checkbox" v-model="form.is_active" id="is_active"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <label for="is_active" class="text-sm text-gray-700 cursor-pointer">
                            Категория активна
                        </label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Создать категорию' }}
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
