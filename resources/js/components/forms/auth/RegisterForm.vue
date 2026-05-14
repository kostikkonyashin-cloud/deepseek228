<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">ФИО</label>
            <input id="full_name" type="text" v-model="form.full_name" required autofocus
                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                :class="{ 'border-red-500': form.errors.full_name }">
            <p v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(form.errors.full_name) ? form.errors.full_name[0] : form.errors.full_name }}
            </p>
        </div>

        <div>
            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Логин</label>
            <input id="login" type="text" v-model="form.login" required
                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                :class="{ 'border-red-500': form.errors.login }">
            <p v-if="form.errors.login" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(form.errors.login) ? form.errors.login[0] : form.errors.login }}
            </p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" v-model="form.email" required
                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                :class="{ 'border-red-500': form.errors.email }">
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email }}
            </p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
            <input id="password" type="password" v-model="form.password" required
                class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                :class="{ 'border-red-500': form.errors.password }">
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(form.errors.password) ? form.errors.password[0] : form.errors.password }}
            </p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Подтвердите пароль
            </label>
            <input id="password_confirmation" type="password" v-model="form.password_confirmation" required
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" v-model="form.terms" required
                    class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded"
                    :class="{ 'border-red-500': form.errors.terms }">
                <span class="ml-2 text-sm text-gray-700">
                    Я принимаю <Link href="#" class="text-black underline">условия соглашения</Link>
                </span>
            </label>
            <p v-if="form.errors.terms" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(form.errors.terms) ? form.errors.terms[0] : form.errors.terms }}
            </p>
        </div>

        <p v-if="form.errors.general" class="text-sm text-red-600 text-center">
            {{ form.errors.general }}
        </p>

        <button type="submit" :disabled="form.processing"
            class="w-full bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span v-if="form.processing">Загрузка...</span>
            <span v-else>Зарегистрироваться</span>
        </button>
    </form>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    full_name: '',
    login: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false
})

const submit = () => {
    form.post(route('register.store'), {
        onError: (errors) => {
            console.log('Ошибки валидации:', errors)
        },
        onSuccess: () => {
            console.log('Регистрация успешна')
        }
    })
}
</script>
