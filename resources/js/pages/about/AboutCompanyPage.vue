<template>
    <AppBaseLayout>
        <div class="min-h-screen bg-gray-100">
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-3xl font-bold mb-8 text-center">Где мы находимся</h1>

                <div class="flex flex-col lg:flex-row gap-8">
                    <Map />

                    <div class="lg:w-1/3 space-y-6">
                        <div
                            class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-bold mb-4 text-blue-600">Наш адрес</h2>
                            <div class="space-y-2">
                                <p class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-gray-700 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>г. Иркутск, ул. Горького, 42</span>
                                </p>
                                <p class="text-sm text-gray-500 pl-7">Цокольный этаж</p>
                            </div>
                        </div>

                        <div
                            class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-bold mb-4 text-blue-600">Часы работы</h2>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-700">Пн-Пт:</span>
                                    <span class="font-medium">10:00 - 20:00</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-700">Сб-Вс:</span>
                                    <span class="font-medium">11:00 - 18:00</span>
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-bold mb-4 text-blue-600">Контакты</h2>
                            <div class="space-y-3">
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <a href="tel:+73952223344" class="hover:text-blue-600 transition-colors">+7 (3952)
                                        22-33-44</a>
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <a href="mailto:info@sinshop.ru"
                                        class="hover:text-blue-600 transition-colors">info@sinshop.ru</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Как добраться -->
                <div class="mt-12 bg-white p-6 rounded-xl shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-center">Как добраться</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="font-semibold mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                    </path>
                                </svg>
                                На машине
                            </h3>
                            <p class="text-gray-700">Удобная парковка перед магазином. Координаты для навигатора:
                                52.285429,
                                104.288476</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="font-semibold mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Пешком
                            </h3>
                            <p class="text-gray-700">5 минут от остановки "Улица Горького". Мы находимся в центре
                                города.
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="font-semibold mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Общественный транспорт
                            </h3>
                            <p class="text-gray-700">Автобусы: 4, 8, 11, 42. Трамваи: 1, 2, 4. Остановка "Улица
                                Горького".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppBaseLayout>
</template>

<script setup>
import AppBaseLayout from '../../layouts/AppBaseLayout.vue';
import Map from '../../components/other/Map.vue';
</script>

<style scoped>
.bg-white {
    transition: all 0.3s ease;
}

@media (min-width: 1024px) {
    .bg-gray-200 {
        min-height: 500px;
    }
}
</style>
