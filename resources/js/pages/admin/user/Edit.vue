<script setup>
import { useForm } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    full_name: props.user.full_name,
    login: props.user.login,
    email: props.user.email,
    role_id: props.user.role_id,
    is_blocked: props.user.is_blocked || false,
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.put(route('admin.user.update', props.user.id))
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Редактирование пользователя</h3>
                    <Link :href="route('admin.user.index')"
                        class="text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Назад к списку
                    </Link>
                </div>

                <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                    <!-- ФИО -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ФИО <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.full_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.full_name }">
                        <p v-if="form.errors.full_name" class="mt-1 text-sm text-red-500">
                            {{ form.errors.full_name }}
                        </p>
                    </div>

                    <!-- Логин -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Логин <span class="text-red-500">*</span>
                        </label>
                        <input type="text" v-model="form.login" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.login }">
                        <p v-if="form.errors.login" class="mt-1 text-sm text-red-500">
                            {{ form.errors.login }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" v-model="form.email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.email }">
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Роль -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Роль <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.role_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.role_id }">
                            <option value="">Выберите роль</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.role_id" class="mt-1 text-sm text-red-500">
                            {{ form.errors.role_id }}
                        </p>
                    </div>

                    <!-- Блокировка -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" v-model="form.is_blocked" id="is_blocked"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <label for="is_blocked" class="text-sm text-gray-700 cursor-pointer">
                            Заблокировать пользователя
                        </label>
                    </div>

                    <!-- Смена пароля -->
                    <div class="border-t border-gray-200 pt-6 mt-2">
                        <h4 class="text-md font-medium text-gray-800 mb-4">Смена пароля</h4>
                        <p class="text-sm text-gray-500 mb-4">Оставьте поля пустыми, если не хотите менять пароль</p>

                        <!-- Новый пароль -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Новый пароль
                            </label>
                            <input type="password" v-model="form.password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                :class="{ 'border-red-500': form.errors.password }">
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Подтверждение пароля
                            </label>
                            <input type="password" v-model="form.password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <!-- Общие ошибки -->
                    <p v-if="form.errors.general" class="text-sm text-red-500">
                        {{ form.errors.general }}
                    </p>

                    <!-- Кнопки -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Сохранить изменения' }}
                        </button>
                        <Link :href="route('admin.user.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                        Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </template>
    </ProfileLayout>
</template>
