<!-- resources/js/Pages/Admin/Team/Create.vue -->
<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    surname: '',
    name: '',
    position: '',
    description: '',
    image: null,
});

const imagePreview = ref(null);
const imageError = ref('');

// Предпросмотр изображения
const onImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        // Проверка типа файла
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            imageError.value = 'Можно загружать только изображения (JPEG, PNG, GIF)';
            form.image = null;
            imagePreview.value = null;
            return;
        }

        // Проверка размера (2MB)
        if (file.size > 2 * 1024 * 1024) {
            imageError.value = 'Размер изображения не должен превышать 2MB';
            form.image = null;
            imagePreview.value = null;
            return;
        }

        imageError.value = '';
        form.image = file;

        // Создаем превью
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Удаление изображения
const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    imageError.value = '';
};

// Сохранение
const submitForm = () => {
    form.post(route('admin.team.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
};
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="max-w-2xl mx-auto">
                <!-- Заголовок -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Добавление участника команды</h1>
                    <p class="text-gray-500 mt-1">Заполните информацию о новом члене команды</p>
                </div>

                <!-- Форма -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <form @submit.prevent="submitForm">
                        <!-- Фамилия -->
                        <div class="mb-4">
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">
                                Фамилия
                            </label>
                            <input id="surname" type="text" v-model="form.surname"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': form.errors.surname }" />
                            <div v-if="form.errors.surname" class="text-red-500 text-sm mt-1">
                                {{ form.errors.surname }}
                            </div>
                        </div>

                        <!-- Имя -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Имя
                            </label>
                            <input id="name" type="text" v-model="form.name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': form.errors.name }" />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Должность -->
                        <div class="mb-4">
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                                Должность
                            </label>
                            <input id="position" type="text" v-model="form.position"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': form.errors.position }"
                                placeholder="Например: Главный механик" />
                            <div v-if="form.errors.position" class="text-red-500 text-sm mt-1">
                                {{ form.errors.position }}
                            </div>
                        </div>

                        <!-- Описание -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Описание
                            </label>
                            <textarea id="description" v-model="form.description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition resize-none"
                                :class="{ 'border-red-500': form.errors.description }"
                                placeholder="Расскажите о сотруднике, его опыте, достижениях..."></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Фото -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Фото
                            </label>

                            <!-- Превью изображения -->
                            <div v-if="imagePreview" class="mb-3">
                                <div class="relative inline-block">
                                    <img :src="imagePreview" alt="Preview"
                                        class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
                                    <button type="button" @click="removeImage"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Кнопка загрузки -->
                            <div v-else class="flex items-center justify-center w-full">
                                <label
                                    class="w-full flex flex-col items-center px-4 py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-100 transition">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Нажмите для выбора фото</span>
                                    <span class="text-xs text-gray-400 mt-1">JPEG, PNG, GIF до 2MB</span>
                                    <input type="file" accept="image/jpeg,image/jpg,image/png,image/gif"
                                        @change="onImageChange" class="hidden" />
                                </label>
                            </div>

                            <div v-if="imageError" class="text-red-500 text-sm mt-2">
                                {{ imageError }}
                            </div>
                            <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">
                                {{ form.errors.image }}
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end gap-3">
                            <Link :href="route('admin.team.index')"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Отмена
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
