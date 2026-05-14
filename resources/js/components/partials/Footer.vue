<template>
    <footer class="bg-gray-50 text-gray-700">
        <div class="px-3 sm:px-4 lg:px-6 py-6 sm:py-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                    <div class="space-y-3 sm:space-y-4">
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Компания</h3>
                        <ul class="space-y-2 sm:space-y-3">
                            <li v-for="link in companyLinks" :key="link.text">
                                <a :href="link.href" class="hover:text-blue-600 transition-colors text-xs sm:text-sm">
                                    {{ link.text }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Помощь</h3>
                        <ul class="space-y-2 sm:space-y-3">
                            <li v-for="link in helpLinks" :key="link.text">
                                <a :href="link.href" class="hover:text-blue-600 transition-colors text-xs sm:text-sm">
                                    {{ link.text }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Информация</h3>
                        <ul class="space-y-2 sm:space-y-3">
                            <li v-for="link in infoLinks" :key="link.text">
                                <a :href="link.href" class="hover:text-blue-600 transition-colors text-xs sm:text-sm">
                                    {{ link.text }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-4 sm:space-y-6">
                        <div class="text-left md:text-right">
                            <p class="font-semibold text-gray-900 text-lg sm:text-xl">8 800 200-11-45</p>
                            <p class="text-gray-500 text-xs sm:text-sm mt-1">Бесплатный звонок по России</p>
                            <a href="#"
                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors text-xs sm:text-sm mt-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.78 5.42-.9 6.8-.06.67-.36.9-.9.9-.66 0-1.63-.49-2.53-.89-1.33-.56-2.1-.91-3.39-1.91-.93-.73-1.58-1.7-2.31-2.74-.75-1.08-.27-1.67.56-2.67.38-.46.85-1.21 1.28-1.21.32 0 .83.22 1.28 1.15.45.93.76 1.54 1.13 1.54.32 0 .8-.49 1.28-1.34.49-.89.85-1.57 1.28-1.57.57 0 .92.53.71 1.57z" />
                                </svg>
                                Задать вопрос в Telegram
                            </a>
                        </div>

                        <div class="flex md:justify-end gap-2 sm:gap-3">
                            <a v-for="social in socialLinks" :key="social.name" :href="social.href"
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 shadow-sm">
                                <img v-if="social.type === 'image'" :src="social.icon" :alt="social.name"
                                    class="w-5 h-5 sm:w-6 sm:h-6">
                                <svg v-else class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path :d="social.icon" />
                                </svg>
                            </a>
                        </div>

                        <div class="text-left md:text-right">
                            <div
                                class="inline-flex items-center bg-yellow-100 px-3 py-2 rounded-full gap-2 text-sm shadow-sm">
                                <span class="font-semibold text-yellow-800">{{ rating.value }}</span>
                                <div class="flex text-yellow-500">
                                    <span v-for="star in rating.stars" :key="star">★</span>
                                </div>
                            </div>
                            <p class="text-gray-500 text-xs sm:text-sm mt-2">{{ rating.label }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-6 sm:mt-8 pt-6 sm:pt-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Копирайт -->
                        <div class="text-center lg:text-left">
                            <p class="text-gray-600 text-xs sm:text-sm">
                                {{ copyright.text }}
                            </p>
                        </div>

                        <!-- Дополнительные ссылки -->
                        <div class="flex flex-wrap justify-center lg:justify-end gap-3 sm:gap-4 text-xs sm:text-sm">
                            <a v-for="link in footerLinks" :key="link.text" :href="link.href"
                                class="text-gray-500 hover:text-gray-700 transition-colors whitespace-nowrap">
                                {{ link.text }}
                            </a>
                        </div>
                    </div>

                    <!-- Платежные системы -->
                    <div class="flex justify-center lg:justify-end mt-4 sm:mt-6">
                        <div
                            class="flex items-center gap-3 bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                            <span class="text-gray-500 text-xs mr-2">Принимаем к оплате:</span>
                            <div class="flex gap-2">
                                <div v-for="payment in paymentSystems" :key="payment"
                                    class="w-8 h-5 bg-gray-200 rounded-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref, computed } from 'vue'

// Навигационные ссылки
const companyLinks = ref([
    { text: 'О компании', href: '#' },
    { text: 'Контакты', href: '#' },
    { text: 'Карьера', href: '#' },
    { text: 'Новости', href: '#' }
])

const helpLinks = ref([
    { text: 'Как сделать заказ?', href: '#' },
    { text: 'Доставка', href: '#' },
    { text: 'Возврат и обмен', href: '#' },
    { text: 'Гарантия', href: '#' },
    { text: 'FAQ', href: '#' },
    { text: 'Словарь терминов', href: '#' }
])

const infoLinks = ref([
    { text: 'Мастерская', href: '#' },
    { text: 'Ближайшие поставки', href: '#' },
    { text: 'Блог', href: '#' },
    { text: 'Бренды', href: '#' },
    { text: 'Команда', href: '#' }
])

const footerLinks = ref([
    { text: 'Оферта', href: '#' },
    { text: 'Политика конфиденциальности', href: '#' },
    { text: 'Пользовательское соглашение', href: '#' },
    { text: 'Карта сайта', href: '#' }
])

// Социальные сети
const socialLinks = ref([
    {
        name: 'VK',
        href: '#',
        type: 'image',
        icon: 'https://upload.wikimedia.org/wikipedia/commons/2/21/VK.com-logo.svg'
    },
    {
        name: 'Telegram',
        href: '#',
        type: 'image',
        icon: 'https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg'
    },
    {
        name: 'Twitter',
        href: '#',
        type: 'svg',
        icon: 'M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z'
    },
    {
        name: 'YouTube',
        href: '#',
        type: 'svg',
        icon: 'M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.042-3.441.219-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.017z'
    }
])

// Платежные системы
const paymentSystems = ref(['visa', 'mastercard', 'mir', 'paypal'])

// Рейтинг
const rating = computed(() => ({
    value: '5.0',
    stars: 5,
    label: 'Рейтинг магазина на Яндекс.Маркете'
}))

// Копирайт
const copyright = computed(() => ({
    text: `© ${new Date().getFullYear()} SiniShop.ru — магазин трюковых самокатов и велосипедов. Продажа самокатов и велосипедов, запчастей, аксессуаров, экипировки и одежды.`
}))
</script>

<style scoped>
/* Плавные переходы для всех интерактивных элементов */
a,
button {
    transition: all 0.2s ease-in-out;
}

/* Улучшенные ховер-эффекты */
.hover\:text-blue-600:hover {
    transform: translateX(2px);
}

/* Адаптивные иконки социальных сетей */
@media (max-width: 475px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
