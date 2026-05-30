<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()

const categories = computed(() => page.props.categories || [])


const isAuthenticated = computed(() => page.props.isAuthenticated || false)

const showMobileMenu = ref(false)
const showMobileSearch = ref(false)
const searchQuery = ref('')

// Состояние для карусели категорий
const scrollContainer = ref(null)
const canScrollLeft = ref(false)
const canScrollRight = ref(false)

// Функции для карусели
const checkScrollButtons = () => {
    if (scrollContainer.value) {
        canScrollLeft.value = scrollContainer.value.scrollLeft > 0
        canScrollRight.value =
            scrollContainer.value.scrollLeft <
            scrollContainer.value.scrollWidth - scrollContainer.value.clientWidth - 5
    }
}

const scroll = (direction) => {
    if (scrollContainer.value) {
        const scrollAmount = direction === 'left' ? -300 : 300
        scrollContainer.value.scrollBy({ left: scrollAmount, behavior: 'smooth' })
        // Проверяем кнопки после анимации
        setTimeout(checkScrollButtons, 300)
    }
}

// Следим за изменением размера окна
const handleResize = () => {
    checkScrollButtons()
}

// Инициализация при монтировании
import { onMounted, onUnmounted, nextTick } from 'vue'

onMounted(() => {
    nextTick(() => {
        checkScrollButtons()
        if (scrollContainer.value) {
            scrollContainer.value.addEventListener('scroll', checkScrollButtons)
        }
        window.addEventListener('resize', handleResize)
    })
})

onUnmounted(() => {
    if (scrollContainer.value) {
        scrollContainer.value.removeEventListener('scroll', checkScrollButtons)
    }
    window.removeEventListener('resize', handleResize)
})

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value
    if (showMobileMenu.value) {
        showMobileSearch.value = false
    }
}

const toggleMobileSearch = () => {
    showMobileSearch.value = !showMobileSearch.value
    if (showMobileSearch.value) {
        showMobileMenu.value = false
    }
}

const performSearch = () => {
    if (searchQuery.value.trim()) {
        router.get(route('search.index'), {
            title: searchQuery.value.trim()
        }, {
            preserveState: true,
            preserveScroll: true
        })
        showMobileSearch.value = false
        showMobileMenu.value = false
    }
}
</script>

<template>
    <header class="bg-white shadow w-full sticky top-0 z-50">
        <div class="w-full px-4">
            <div class="flex items-center justify-between p-4 max-w-7xl mx-auto">
                <div class="flex items-center gap-4 lg:w-[70%]">
                    <button @click="toggleMobileMenu" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <Link :href="route('index')" class="flex items-center gap-1 shrink-0">
                        <img :src="`${route('index')}/storage/images/logo.png`" alt="логотип"
                            class="w-20 h-16 lg:w-25 lg:h-20">
                    </Link>

                    <div class="relative w-full max-w-4xl mx-4 hidden lg:block">
                        <div class="relative">
                            <input type="text" v-model="searchQuery" @keyup.enter="performSearch"
                                placeholder="Поиск товаров..." autocomplete="off"
                                class="border border-gray-200 px-4 py-2 w-full rounded-lg bg-gray-100 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" @click="performSearch"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 lg:gap-12 text-sm">
                    <div class="hidden lg:flex flex-col">
                        <span class="font-bold text-lg ml-4">8 983 402-06-63</span>
                        <a href="#" class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span>Задать вопрос в Telegram</span>
                        </a>
                    </div>

                    <button @click="toggleMobileSearch" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Избранное (только для авторизованных) -->
                    <Link :href="route('favorites.index')" v-if="isAuthenticated" class="flex flex-col items-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="hidden sm:block">Избранное</span>
                    </Link>

                    <!-- Корзина -->
                    <Link :href="route('cart.index')" class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 1.5M17 13l1.5 1.5M9 21a1 1 0 100-2 1 1 0 000 2zM17 21a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                        <span class="hidden sm:block">Корзина</span>
                    </Link>

                    <!-- Профиль/Вход -->
                    <Link :href="isAuthenticated ? route('profile.index') : route('login.create')"
                        class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAuthenticated
                                ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
                                : 'M5.636 5.636a9 9 0 1012.728 0M12 3v9'" />
                        </svg>
                        <span class="hidden sm:block">{{ isAuthenticated ? 'Кабинет' : 'Войти' }}</span>
                    </Link>
                </div>
            </div>

            <div v-if="showMobileSearch" class="lg:hidden px-4 pb-4">
                <div class="relative">
                    <input type="text" v-model="searchQuery" @keyup.enter="performSearch" placeholder="Поиск товаров..."
                        autocomplete="off"
                        class="border border-gray-200 px-4 py-3 w-full rounded-lg bg-gray-100 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" @click="performSearch"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <nav class="w-full px-4">
            <div class="w-full max-w-7xl mx-auto relative">
                <!-- Контейнер с каруселью -->
                <div class="relative flex items-center">
                    <!-- Кнопка "Назад" -->
                    <button v-if="canScrollLeft" @click="scroll('left')"
                        class="absolute left-0 z-10 bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-all -translate-x-2"
                        :class="{ 'opacity-100': canScrollLeft, 'opacity-0 pointer-events-none': !canScrollLeft }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Скроллящийся контейнер -->
                    <div ref="scrollContainer" class="flex gap-8 px-4 py-3 overflow-x-auto scroll-smooth hide-scrollbar"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <Link v-for="category in categories" :key="category.id"
                            :href="route('product.index', { category: category.slug })"
                            class="flex items-center gap-2 shrink-0 hover:text-blue-600 transition-colors whitespace-nowrap">
                            <img v-if="category.icon_path" :src="`${route('index')}${category.icon_path}`"
                                :alt="category.name" class="w-5 h-5">
                            <span>{{ category.name }}</span>
                        </Link>

                        <Link :href="route('brand.index')"
                            class="flex items-center gap-1 shrink-0 hover:text-blue-600 transition-colors whitespace-nowrap">
                            <span>Бренды</span>
                        </Link>

                        <Link :href="route('about-company.index')"
                            class="flex items-center gap-2 shrink-0 hover:text-blue-600 transition-colors whitespace-nowrap">
                            <img :src="`${route('index')}/storage/images/miniicon10.png`" class="w-5 h-5">
                            <span>Где мы находимся?</span>
                        </Link>
                    </div>

                    <!-- Кнопка "Вперед" -->
                    <button v-if="canScrollRight" @click="scroll('right')"
                        class="absolute right-0 z-10 bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-all translate-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Мобильное меню (выпадающее) -->
                <div v-if="showMobileMenu" class="lg:hidden fixed inset-0 z-50 bg-white pt-20 px-4 overflow-y-auto">
                    <button @click="toggleMobileMenu" class="absolute top-4 right-4 p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <div class="font-bold text-xl mb-2">8 983 402-06-63</div>
                            <a href="#" class="flex items-center gap-2 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <span>Задать вопрос в Telegram</span>
                            </a>
                        </div>

                        <!-- Избранное в мобильном меню -->
                        <div v-if="isAuthenticated" class="space-y-2">
                            <div class="font-semibold text-gray-500 text-sm mb-2">Избранное</div>
                            <div class="flex items-center gap-3 p-3 rounded-lg text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>Мои избранные товары</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="font-semibold text-gray-500 text-sm mb-2">Категории</div>
                            <Link v-for="category in categories" :key="category.id"
                                :href="route('product.index', { category: category.slug })" @click="toggleMobileMenu"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 transition-colors text-lg">
                                <img v-if="category.icon_path" :src="`${route('index')}${category.icon_path}`"
                                    :alt="category.name" class="w-5 h-5">
                                <span>{{ category.name }}</span>
                            </Link>

                            <Link :href="route('brand.index')" @click="toggleMobileMenu"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 transition-colors text-lg">
                                <span>Бренды</span>
                            </Link>

                            <Link :href="route('about-company.index')" @click="toggleMobileMenu"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 transition-colors text-lg">
                                <span>Где мы находимся?</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
