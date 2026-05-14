<script setup>
const props = defineProps(['brands']);
import AppBaseLayout from '../layouts/AppBaseLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

// Состояния для слайдера (хотя картинка одна)
const currentBannerIndex = ref(0)
const banners = ref([
    { id: 1, image: `${route('index')}/storage/images/banner.png`, alt: 'Баннер SIN-BMX' }
])

// Автоматическая смена не нужна, так как баннер один
// Но для плавности добавим
const nextBanner = () => {
    if (banners.value.length > 1) {
        currentBannerIndex.value = (currentBannerIndex.value + 1) % banners.value.length
    }
}

const prevBanner = () => {
    if (banners.value.length > 1) {
        currentBannerIndex.value = (currentBannerIndex.value - 1 + banners.value.length) % banners.value.length
    }
}
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <!-- Баннер (слайдер из одного изображения) -->
            <section class="mb-12">
                <div class="relative rounded-2xl overflow-hidden shadow-lg group">
                    <div class="relative aspect-21/9 w-full">
                        <img :src="banners[currentBannerIndex].image" :alt="banners[currentBannerIndex].alt"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>

                    <!-- Кнопки навигации (скрыты, так как баннер один, но можно показать для красоты) -->
                    <button v-if="banners.length > 1" @click="prevBanner"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-10 h-10 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button v-if="banners.length > 1" @click="nextBanner"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-10 h-10 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Индикатор (точки) - для одного баннера можно не показывать -->
                    <div v-if="banners.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                        <button v-for="(_, idx) in banners" :key="idx" @click="currentBannerIndex = idx" :class="[
                            'w-2 h-2 rounded-full transition-all duration-300',
                            currentBannerIndex === idx ? 'bg-white w-6' : 'bg-white/50'
                        ]"></button>
                    </div>
                </div>
            </section>

            <section class="mb-16">
                <div class="flex justify-between items-end mb-6">
                    <Link :href="route('brand.index')"
                        class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8 group hover:text-purple-600 transition-colors duration-300">
                        Наши бренды
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800 group-hover:bg-purple-600 transition-colors duration-300"></span>
                    </Link>
                </div>
                <div class="overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-300">
                    <div class="flex gap-6 min-w-max">
                        <Link :href="route('brand.show', {
                            brand: brand.slug
                        })" v-for="brand in brands" :key="brand.id"
                            class="flex flex-col items-center gap-2 min-w-25 group cursor-pointer">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center group-hover:shadow-md transition">
                                <img v-if="brand.image_path" :src="brand.image_path" :alt="brand.name"
                                    class="w-12 h-12 object-contain">
                                <svg v-else class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 10h-3v3c0 .6-.4 1-1 1s-1-.4-1-1v-3h-3c-.6 0-1-.4-1-1s.4-1 1-1h3V9c0-.6.4-1 1-1s1 .4 1 1v3h3c.6 0 1 .4 1 1s-.4 1-1 1z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 group-hover:text-purple-600 transition">{{ brand.name
                                }}</span>
                        </Link>
                    </div>
                </div>
            </section>

            <section class="mb-16">
                <Link
                    class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8 group hover:text-purple-600 transition-colors duration-300">
                    Мы в соцсетях
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800 group-hover:bg-purple-600 transition-colors duration-300"></span>
                </Link>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="#" target="_blank" rel="noopener noreferrer"
                        class="relative overflow-hidden rounded-xl group aspect-video">
                        <img :src="`${route('index')}/storage/images/tgbanner.png`" alt="Telegram"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xl font-semibold">Telegram</span>
                        </div>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer"
                        class="relative overflow-hidden rounded-xl group aspect-video">
                        <img :src="`${route('index')}/storage/images/vkbanner.png`" alt="VK"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xl font-semibold">VK</span>
                        </div>
                    </a>
                </div>
            </section>

            <section class="mb-16">
                <Link :href="route('about-company.index')"
                    class="text-2xl font-semibold text-gray-800 relative inline-block pb-2 mb-8 group hover:text-purple-600 transition-colors duration-300">
                    Почему выбирают нас
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-800 group-hover:bg-purple-600 transition-colors duration-300"></span>
                </Link>
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
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
