<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const form = useForm({
    name: '',
    image: null,
    is_active: true
})

const imagePreview = ref(null)

const onFileChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.image = file
        // Используем FileReader вместо URL.createObjectURL
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const removeImage = () => {
    imagePreview.value = null
    form.image = null
}

const submit = () => {
    form.post(route('admin.brand.store'), {
        onSuccess: (message) => {
            console.log(message)
        },
        onError: (message) => {
            console.log(message)
        },
    })
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Создание бренда</h3>
                    <Link :href="route('admin.brand.index')"
                        class="text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Назад к списку
                    </Link>
                </div>

                <form @submit.prevent="submit" class="max-w-2xl space-y-6" enctype="multipart/form-data">
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

                    <!-- Изображение -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Логотип</label>
                        <div class="mt-1 flex items-center gap-4">
                            <div v-if="imagePreview" class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden relative">
                                <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                            <div v-else class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <input type="file" @change="onFileChange"
                                    accept="image/jpeg,image/png,image/jpg,image/svg,image/webp"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                <p class="text-xs text-gray-400 mt-1">Допустимые форматы: JPEG, PNG, JPG, SVG, WEBP.
                                    Максимум 2MB</p>
                            </div>
                        </div>
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-500">{{ form.errors.image }}</p>
                    </div>

                    <!-- Активность -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" v-model="form.is_active" id="is_active"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <label for="is_active" class="text-sm text-gray-700 cursor-pointer">
                            Бренд активен
                        </label>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Создать бренд' }}
                        </button>
                        <Link :href="route('admin.brand.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                            Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </template>
    </ProfileLayout>
</template>
