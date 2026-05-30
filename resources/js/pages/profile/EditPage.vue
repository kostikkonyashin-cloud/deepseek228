<!-- resources/js/Pages/Profile/Edit.vue -->
<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object
});

// Форма для изменения логина
const loginForm = useForm({
    login: props.user.login,
});

// Форма для изменения пароля
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Состояние для отображения формы смены пароля
const showPasswordForm = ref(false);

// Обновление логина
const updateLogin = () => {
    loginForm.put(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            loginForm.reset();
        },
    });
};

// Обновление пароля
const updatePassword = () => {
    passwordForm.put(route('profile.update-password'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            showPasswordForm.value = false;
        },
    });
};
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="max-w-2xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Редактирование профиля</h1>
                    <p class="text-gray-500 mt-1">Измените свои данные</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Изменение логина</h2>

                    <form @submit.prevent="updateLogin">
                        <div class="mb-4">
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                                Логин
                            </label>
                            <input id="login" type="text" v-model="loginForm.login"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': loginForm.errors.login }" />
                            <div v-if="loginForm.errors.login" class="text-red-500 text-sm mt-1">
                                {{ loginForm.errors.login }}
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="loginForm.processing"
                                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                {{ loginForm.processing ? 'Сохранение...' : 'Сохранить' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Изменение пароля</h2>
                        <button @click="showPasswordForm = !showPasswordForm" type="button"
                            class="text-gray-600 hover:text-gray-800 text-sm font-medium cursor-pointer">
                            {{ showPasswordForm ? 'Отмена' : 'Изменить пароль' }}
                        </button>
                    </div>

                    <form v-if="showPasswordForm" @submit.prevent="updatePassword">
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Текущий пароль
                            </label>
                            <input id="current_password" type="password" v-model="passwordForm.current_password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': passwordForm.errors.current_password }" />
                            <div v-if="passwordForm.errors.current_password" class="text-red-500 text-sm mt-1">
                                {{ passwordForm.errors.current_password }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Новый пароль
                            </label>
                            <input id="password" type="password" v-model="passwordForm.password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': passwordForm.errors.password }" />
                            <div v-if="passwordForm.errors.password" class="text-red-500 text-sm mt-1">
                                {{ passwordForm.errors.password }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Подтверждение нового пароля
                            </label>
                            <input id="password_confirmation" type="password"
                                v-model="passwordForm.password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-400 outline-none transition"
                                :class="{ 'border-red-500': passwordForm.errors.password_confirmation }" />
                            <div v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-sm mt-1">
                                {{ passwordForm.errors.password_confirmation }}
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="passwordForm.processing"
                                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ passwordForm.processing ? 'Сохранение...' : 'Изменить пароль' }}
                            </button>
                        </div>
                    </form>

                    <div v-else class="text-gray-500 text-sm py-4">
                        Нажмите «Изменить пароль», чтобы обновить пароль
                    </div>
                </div>

                <!-- Кнопка возврата -->
                <div class="mt-6 text-center">
                    <Link :href="route('profile.index')" class="text-gray-500 hover:text-gray-700 transition">
                        ← Вернуться в профиль
                    </Link>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
