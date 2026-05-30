<script setup>
const props = defineProps(['brands', 'members', 'services']);
import AppBaseLayout from '../layouts/AppBaseLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const categories = computed(() => page.props.categories || []);

const achievements = computed(() => [
    {
        id: 1,
        title: 'Работаем с 2012',
        description: 'Более 13 лет на рынке',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
    },
    {
        id: 2,
        title: 'Официальная гарантия',
        description: 'От производителя',
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
    },
    {
        id: 3,
        title: 'Про-консультации',
        description: 'Помощь на каждом этапе',
        icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 00-4-4m4 4v3m0 0v3m0-3h3m-3 0h-3M5 11a4 4 0 00-4-4m4 4v3m0 0v3m0-3h3m-3 0H2'
    },
    {
        id: 4,
        title: 'Крупнейший ассортимент',
        description: 'В самокатной сфере',
        icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
    }
])

// Состояния для основного слайдера
const currentSlideIndex = ref(0)
const sliderContainer = ref(null)
const fullImagePath = `${route('index')}/storage/images/banner.png`

const slides = ref([
    {
        id: 1,
        image: fullImagePath,
        alt: 'Баннер часть 1',
        title: 'Электросамокаты',
        description: 'Новинки 2024 года уже в наличии',
        objectPosition: '0% 50%'
    },
    {
        id: 2,
        image: fullImagePath,
        alt: 'Баннер часть 2',
        title: 'Аксессуары',
        description: 'Защита, сумки, запчасти и многое другое',
        objectPosition: '50% 50%'
    },
    {
        id: 3,
        image: fullImagePath,
        alt: 'Баннер часть 3',
        title: 'Сервисное обслуживание',
        description: 'Ремонт и тюнинг любой сложности',
        objectPosition: '100% 50%'
    }
])

// Состояния для слайдеров
const teamSliderContainer = ref(null)
const canScrollTeamLeft = ref(false)
const canScrollTeamRight = ref(false)

const servicesSliderContainer = ref(null)
const canScrollServiceLeft = ref(false)
const canScrollServiceRight = ref(false)

const brandsSliderContainer = ref(null)
const canScrollBrandLeft = ref(false)
const canScrollBrandRight = ref(false)

// Функция для обработки ошибок загрузки изображений
const handleImageError = (event) => {
    event.target.style.display = 'none'
    const parent = event.target.parentElement
    const placeholder = parent?.querySelector('.image-placeholder')
    if (placeholder) {
        placeholder.style.display = 'flex'
    }
}

// Функции для основного слайдера
const nextSlide = () => {
    if (currentSlideIndex.value < slides.value.length - 1) {
        currentSlideIndex.value++
        scrollToSlide(currentSlideIndex.value)
    } else {
        goToSlide(0)
    }
}

const prevSlide = () => {
    if (currentSlideIndex.value > 0) {
        currentSlideIndex.value--
        scrollToSlide(currentSlideIndex.value)
    } else {
        goToSlide(slides.value.length - 1)
    }
}

const scrollToSlide = (index) => {
    if (sliderContainer.value) {
        const slideWidth = sliderContainer.value.clientWidth
        sliderContainer.value.scrollTo({
            left: slideWidth * index,
            behavior: 'smooth'
        })
    }
}

const goToSlide = (index) => {
    currentSlideIndex.value = index
    scrollToSlide(index)
}

const handleScroll = () => {
    if (sliderContainer.value) {
        const scrollPosition = sliderContainer.value.scrollLeft
        const slideWidth = sliderContainer.value.clientWidth
        const newIndex = Math.round(scrollPosition / slideWidth)
        if (newIndex !== currentSlideIndex.value && newIndex >= 0 && newIndex < slides.value.length) {
            currentSlideIndex.value = newIndex
        }
    }
}

// Универсальная функция для проверки кнопок слайдеров
const checkScrollButtons = (container, canLeft, canRight) => {
    if (container.value) {
        canLeft.value = container.value.scrollLeft > 20
        canRight.value = container.value.scrollLeft < container.value.scrollWidth - container.value.clientWidth - 20
    }
}

// Универсальная функция для скролла слайдеров
const scrollSlider = (container, direction, canLeftRef, canRightRef) => {
    if (container.value) {
        const scrollAmount = direction === 'left' ? -280 : 280
        container.value.scrollBy({ left: scrollAmount, behavior: 'smooth' })
        setTimeout(() => {
            checkScrollButtons(container, canLeftRef, canRightRef)
        }, 300)
    }
}

// Автоматическое переключение основного слайдера
let autoplayInterval = null

const startAutoplay = () => {
    if (slides.value.length > 1) {
        autoplayInterval = setInterval(() => {
            nextSlide()
        }, 3000)
    }
}

const stopAutoplay = () => {
    if (autoplayInterval) {
        clearInterval(autoplayInterval)
        autoplayInterval = null
    }
}

onMounted(() => {
    nextTick(() => {
        if (sliderContainer.value) {
            sliderContainer.value.addEventListener('scroll', handleScroll)
        }
        startAutoplay()

        // Инициализация слайдеров
        if (teamSliderContainer.value) {
            teamSliderContainer.value.addEventListener('scroll', () => checkScrollButtons(teamSliderContainer, canScrollTeamLeft, canScrollTeamRight))
            checkScrollButtons(teamSliderContainer, canScrollTeamLeft, canScrollTeamRight)
        }
        if (servicesSliderContainer.value) {
            servicesSliderContainer.value.addEventListener('scroll', () => checkScrollButtons(servicesSliderContainer, canScrollServiceLeft, canScrollServiceRight))
            checkScrollButtons(servicesSliderContainer, canScrollServiceLeft, canScrollServiceRight)
        }
        if (brandsSliderContainer.value) {
            brandsSliderContainer.value.addEventListener('scroll', () => checkScrollButtons(brandsSliderContainer, canScrollBrandLeft, canScrollBrandRight))
            checkScrollButtons(brandsSliderContainer, canScrollBrandLeft, canScrollBrandRight)
        }
    })
})

onUnmounted(() => {
    if (sliderContainer.value) {
        sliderContainer.value.removeEventListener('scroll', handleScroll)
    }
    stopAutoplay()
})
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <!-- Основной баннер слайдер -->
            <section class="mb-12">
                <div class="relative rounded-2xl overflow-hidden shadow-lg group">
                    <div ref="sliderContainer" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth"
                        style="scrollbar-width: none; -ms-overflow-style: none;" @scroll="handleScroll">
                        <div v-for="(slide, idx) in slides" :key="slide.id" class="relative min-w-full snap-start">
                            <div class="relative aspect-21/9 w-full">
                                <img :src="slide.image" :alt="slide.alt" class="w-full h-full object-cover"
                                    :style="{ objectPosition: slide.objectPosition }">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex items-center">
                                    <div class="ml-8 md:ml-16 text-white">
                                        <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">{{
                                            slide.title }}</h2>
                                        <p class="text-sm md:text-lg mb-4 md:mb-6">{{ slide.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button v-if="slides.length > 1" @click="prevSlide"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button v-if="slides.length > 1" @click="nextSlide"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div v-if="slides.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                        <button v-for="(_, idx) in slides" :key="idx" @click="goToSlide(idx)" :class="[
                            'w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-all duration-300',
                            currentSlideIndex === idx ? 'bg-white w-4 md:w-6' : 'bg-white/50 hover:bg-white/80'
                        ]"></button>
                    </div>
                </div>
            </section>

            <!-- Секция категорий -->
            <section class="mb-16" v-if="categories.length > 0">
                <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8">
                    Категории
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link v-for="category in categories" :key="category.id"
                        :href="route('product.index', { category: category.slug })"
                        class="group relative overflow-hidden rounded-xl bg-gray-50 border border-gray-100 hover:shadow-lg transition-all duration-300">
                        <div class="aspect-square relative overflow-hidden">
                            <div class="relative w-full h-full">
                                <img v-if="category.image_path" :src="`${route('index')}${category.image_path}`"
                                    :alt="category.name" class="w-full h-full object-cover" @error="handleImageError">
                                <div class="image-placeholder w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center gap-2"
                                    :class="{ 'flex': !category.image_path, 'hidden': category.image_path }">
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-500 text-sm">Нет изображения</span>
                                </div>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <span class="text-white font-semibold text-lg">{{ category.name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-medium text-gray-800 group-hover:text-purple-600 transition-colors">
                                {{ category.name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Перейти в категорию →</p>
                        </div>
                    </Link>
                </div>
            </section>

            <section class="mb-16" v-if="services && services.length > 0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2">
                        Услуги мастерской
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                    </h2>
                    <Link :href="route('workshop.index')"
                        class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                        Все услуги →
                    </Link>
                </div>

                <div class="relative">
                    <button v-if="canScrollServiceLeft"
                        @click="scrollSlider(servicesSliderContainer, 'left', canScrollServiceLeft, canScrollServiceRight)"
                        class="absolute -left-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div ref="servicesSliderContainer"
                        class="flex gap-5 overflow-x-auto scroll-smooth hide-scrollbar pb-2"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <div v-for="service in services" :key="service.id"
                            class="group flex-shrink-0 w-56 h-72 rounded-xl overflow-hidden relative shadow-md hover:shadow-xl transition-all duration-300">
                            <!-- Фоновое изображение -->
                            <div class="absolute inset-0 w-full h-full">
                                <img v-if="service.image_path" :src="service.image_path" :alt="service.title"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 to-purple-700"></div>
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                </div>
                            </div>

                            <!-- Контент - просто текст прижатый к низу -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <h3 class="font-bold text-lg mb-1">{{ service.title }}</h3>
                                <p class="text-xl font-bold text-yellow-300">
                                    {{ service.price ? service.price.toLocaleString() + ' ₽' : 'Договорная' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button v-if="canScrollServiceRight"
                        @click="scrollSlider(servicesSliderContainer, 'right', canScrollServiceLeft, canScrollServiceRight)"
                        class="absolute -right-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </section>

            <!-- Слайдер команды (текст внизу) -->
            <section class="mb-16" v-if="members && members.length > 0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2">
                        Наша команда
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                    </h2>
                </div>

                <div class="relative">
                    <button v-if="canScrollTeamLeft"
                        @click="scrollSlider(teamSliderContainer, 'left', canScrollTeamLeft, canScrollTeamRight)"
                        class="absolute -left-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div ref="teamSliderContainer" class="flex gap-5 overflow-x-auto scroll-smooth hide-scrollbar pb-2"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <Link v-for="member in members" :key="member.id"
                            class="group flex-shrink-0 w-48 h-56 rounded-xl overflow-hidden relative shadow-md hover:shadow-xl transition-all duration-300">
                            <!-- Фоновое изображение -->
                            <div class="absolute inset-0 w-full h-full">
                                <img v-if="member.image_path" :src="member.image_path" :alt="member.name"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div v-else
                                    class="w-full h-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <!-- Темный оверлей для читаемости текста -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                </div>
                            </div>

                            <!-- Контент поверх изображения - прижат к низу -->
                            <div class="absolute bottom-0 left-0 right-0 p-3 text-white">
                                <h3 class="font-bold text-base mb-0.5 line-clamp-1">{{ member.name }} {{ member.surname
                                    }}</h3>
                                <p class="text-xs text-gray-200 line-clamp-1">{{ member.position }}</p>
                            </div>
                        </Link>
                    </div>

                    <button v-if="canScrollTeamRight"
                        @click="scrollSlider(teamSliderContainer, 'right', canScrollTeamLeft, canScrollTeamRight)"
                        class="absolute -right-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </section>

            <!-- Слайдер брендов -->
            <section class="mb-16" v-if="brands && brands.length > 0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2">
                        Наши бренды
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                    </h2>
                    <Link :href="route('brand.index')" class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                        Все бренды →
                    </Link>
                </div>

                <div class="relative">
                    <button v-if="canScrollBrandLeft"
                        @click="scrollSlider(brandsSliderContainer, 'left', canScrollBrandLeft, canScrollBrandRight)"
                        class="absolute -left-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div ref="brandsSliderContainer"
                        class="flex gap-6 overflow-x-auto scroll-smooth hide-scrollbar pb-2"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <Link v-for="brand in brands" :key="brand.id" :href="route('brand.show', { brand: brand.slug })"
                            class="flex-shrink-0 flex flex-col items-center gap-2 group">
                            <div
                                class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center group-hover:shadow-md transition border border-gray-100 p-3">
                                <img v-if="brand.image_path" :src="brand.image_path" :alt="brand.name"
                                    class="w-full h-full object-contain">
                                <svg v-else class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 10h-3v3c0 .6-.4 1-1 1s-1-.4-1-1v-3h-3c-.6 0-1-.4-1-1s.4-1 1-1h3V9c0-.6.4-1 1-1s1 .4 1 1v3h3c.6 0 1 .4 1 1s-.4 1-1 1z" />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600 group-hover:text-purple-600 transition text-center">{{
                                brand.name }}</span>
                        </Link>
                    </div>

                    <button v-if="canScrollBrandRight"
                        @click="scrollSlider(brandsSliderContainer, 'right', canScrollBrandLeft, canScrollBrandRight)"
                        class="absolute -right-3 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow-md p-1.5 hover:bg-gray-50 transition border border-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </section>

            <!-- Секция соцсетей -->
            <section class="mb-16">
                <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8">
                    Мы в соцсетях
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="https://t.me/s/SINBMXSHOP" target="_blank" class="relative overflow-hidden rounded-xl group aspect-video">
                        <img :src="`${route('index')}/storage/images/tgbanner.png`" alt="Telegram"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xl font-semibold">Telegram</span>
                        </div>
                    </a>
                    <a href="https://vk.com/sinbmx?ysclid=mpsh0bq9wx373721951" target="_blank" class="relative overflow-hidden rounded-xl group aspect-video">
                        <img :src="`${route('index')}/storage/images/vkbanner.png`" alt="VK"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xl font-semibold">VK</span>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Секция преимуществ -->
            <section class="mb-16">
                <h2 class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8">
                    Почему выбирают нас
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800"></span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="achievement in achievements" :key="achievement.id"
                        class="bg-gray-50 border border-gray-100 rounded-xl p-6 text-center group hover:shadow-lg transition">
                        <div
                            class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-100 transition">
                            <svg class="w-8 h-8 text-gray-600 group-hover:text-purple-600 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="achievement.icon" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ achievement.title }}</h3>
                        <p class="text-sm text-gray-500">{{ achievement.description }}</p>
                    </div>
                </div>
            </section>
        </div>
    </AppBaseLayout>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
