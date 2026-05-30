<!-- resources/js/Pages/Admin/Workshop/Create.vue -->
<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    description: '',
    price: '',
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
    form.post(route('admin.workshop.store'), {
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
                    <div class="flex items-center gap-4">
                        <Link :href="route('admin.workshop.index')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Добавление услуги</h1>
                            <p class="text-gray-500 mt-1">Заполните информацию о новой услуге мастерской</p>
                        </div>
                    </div>
                </div>

                <!-- Форма -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <form @submit.prevent="submitForm">
                        <!-- Название услуги -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Название услуги <span class="text-red-500">*</span>
                            </label>
                            <input id="title" type="text" v-model="form.title"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': form.errors.title }"
                                placeholder="Например: Ремонт электросамокатов" />
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Описание -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Описание <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" v-model="form.description" rows="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition resize-none"
                                :class="{ 'border-red-500': form.errors.description }"
                                placeholder="Подробное описание услуги, что входит, какие работы выполняются..."></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Цена -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Цена
                            </label>
                            <div class="relative">
                                <input id="price" type="number" step="0.01" v-model="form.price"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                    :class="{ 'border-red-500': form.errors.price }" placeholder="0.00" />
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">₽</span>
                            </div>
                            <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">
                                {{ form.errors.price }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Оставьте пустым, если цена договорная</p>
                        </div>

                        <!-- Изображение -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Изображение услуги
                            </label>

                            <!-- Превью изображения -->
                            <div v-if="imagePreview" class="mb-4">
                                <div class="relative inline-block">
                                    <img :src="imagePreview" alt="Preview"
                                        class="w-40 h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                    <button type="button" @click="removeImage"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition shadow-md"
                                        title="Удалить фото">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Кнопка загрузки -->
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="w-full flex flex-col items-center px-4 py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-100 transition group">
                                    <svg class="w-10 h-10 text-gray-400 mb-2 group-hover:text-gray-500 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 group-hover:text-gray-600 transition">
                                        {{ imagePreview ? 'Заменить изображение' : 'Выбрать изображение' }}
                                    </span>
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
                        <div class="flex justify-end gap-3 pt-2">
                            <Link :href="route('admin.workshop.index')"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Отмена
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 cursor-pointer">
                                <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
