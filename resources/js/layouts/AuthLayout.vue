<template>
    <div class="flex items-center justify-center min-h-[calc(100vh-200px)] py-12">
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-md">
            <div class="text-center mb-6">
                <img :src="`${route('index')}/storage/images/logo.png`" alt="Логотип" class="h-12 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Добро пожаловать</h1>
            </div>

            <div class="flex border-b mb-6">
                <Link :href="route('login.create')"
                    class="flex-1 py-2 text-center font-medium border-b-2 transition-colors" :class="[
                        $page.component === 'Auth/LoginPage'
                            ? 'border-black text-black'
                            : 'border-transparent text-gray-500 hover:text-gray-700'
                    ]">
                Вход
                </Link>
                <Link :href="route('register.create')"
                    class="flex-1 py-2 text-center font-medium border-b-2 transition-colors" :class="[
                        $page.component === 'Auth/RegisterPage'
                            ? 'border-black text-black'
                            : 'border-transparent text-gray-500 hover:text-gray-700'
                    ]">
                Регистрация
                </Link>
            </div>

            <slot />
        </div>
    </div>
</template>

<script setup>
import FlashMessage from '../components/other/FlashMesage.vue'
</script>
