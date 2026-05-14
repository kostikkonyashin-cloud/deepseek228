<template>
    <Form>
        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" v-model="form.email" required autofocus
                    class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                    :class="{ 'border-red-500': form.errors.email }">
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                <input id="password" type="password" v-model="form.password" required
                    class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                    :class="{ 'border-red-500': form.errors.password }">
                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                    {{ form.errors.password }}
                </p>
            </div>

            <p v-if="form.errors.general" class="text-sm text-red-600 text-center">
                {{ form.errors.general }}
            </p>

            <button type="submit" :disabled="form.processing"
                class="w-full bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                <span v-if="form.processing">Загрузка...</span>
                <span v-else>Войти</span>
            </button>

            <div class="relative my-6" v-if="socialProviders && socialProviders.length">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Или войдите через</span>
                </div>
            </div>

            <div v-if="socialProviders && socialProviders.length" class="space-y-3">
                <a v-for="provider in socialProviders" :key="provider.id"
                    :href="route('socialite-account.create', { provider: provider.name })"
                    class="w-full flex items-center justify-center gap-3 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer">
                    <span>{{ provider.name === 'yandex' ? 'Яндекс' : provider.name }}</span>
                </a>
            </div>
        </form>
    </Form>
</template>

<script setup>
import { useForm, Form } from '@inertiajs/vue3'

const props = defineProps({
    socialProviders: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post(route('login.store'), {
        onError: (message) => {
            console.log(message)
        },
        onSuccess: (message) => {
            console.log(message)
        }
    })
}
</script>
