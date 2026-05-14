<script setup>
import ProfileLayout from '../../layouts/ProfileLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    orders: {
        type: Array,
        default: () => []
    }
});

const getStatusText = (status) => {
    const statuses = {
        pending: 'Ожидает оплаты',
        processing: 'В обработке',
        completed: 'Выполнен',
        cancelled: 'Отменен'
    }
    return statuses[status] || status
}

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}

// Цена товара со скидкой
const getDiscountedPrice = (product) => {
    let price = product.price
    if (product.discount) {
        price = price * (1 - product.discount / 100)
    }
    return Math.round(price)
}

// Сумма позиции с учетом скидки
const getItemTotal = (item) => {
    const price = getDiscountedPrice(item.product)
    return price * item.quantity
}

const cancelOrder = (order) => {
    if (confirm(`Вы уверены, что хотите отменить заказ №${order.id}?`)) {
        router.delete(route('order.destroy', { order: order.id }), {
            preserveScroll: true
        })
    }
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Мои заказы</h3>
                <div v-if="!orders || orders.length === 0" class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 text-lg">У вас пока нет заказов</p>
                    <p class="text-gray-400 text-sm mt-2">Начните покупки в нашем каталоге</p>
                    <Link :href="route('index')" class="inline-block mt-4 text-purple-600 hover:underline">
                        Перейти к покупкам
                    </Link>
                </div>

                <!-- Список заказов -->
                <div v-else v-for="order in orders" :key="order.id" class="bg-white rounded-xl shadow-sm p-6">
                    <!-- Шапка заказа -->
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 pb-4 border-b">
                        <div>
                            <p class="text-sm text-gray-500">Заказ №{{ order.id }}</p>
                            <p class="text-sm text-gray-500">{{ order.ordered_at }}</p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span :class="['px-3 py-1 rounded-full text-sm font-medium', getStatusColor(order.status)]">
                                {{ getStatusText(order.status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Товары в заказе -->
                    <div class="space-y-3">
                        <div v-for="item in order.order_items" :key="item.id" class="flex gap-4">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-lg shrink-0 flex items-center justify-center overflow-hidden">
                                <img v-if="item.product?.image_url" :src="item.product.image_url"
                                    :alt="item.product?.name || 'Товар'" class="w-full h-full object-cover">
                                <img v-else-if="item.product?.images?.[0]" :src="item.product.images[0]"
                                    :alt="item.product?.name || 'Товар'" class="w-full h-full object-cover">
                                <svg v-else class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <Link :href="route('product.show', {
                                    category: item.product.category.slug,
                                    product: item.product.slug
                                })" class="font-medium text-gray-800 hover:text-purple-600 transition">
                                    {{ item.product.brand?.name }} {{ item.product?.name || 'Товар' }}
                                </Link>

                                <!-- Характеристики -->
                                <p class="text-sm text-gray-500"
                                    v-if="item.color?.name || item.size?.name || item.material?.name">
                                    {{ item.color?.name || '' }} {{ item.size?.name || '' }} {{ item.material?.name ||
                                        '' }}
                                </p>

                                <!-- Цена и количество -->
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ item.quantity }} ×
                                    <span v-if="item.product.discount">
                                        <span class="line-through text-gray-400 mr-1">{{
                                            Number(item.product.price).toLocaleString() }} ₽</span>
                                        <span class="text-green-600">{{
                                            getDiscountedPrice(item.product).toLocaleString() }} ₽</span>
                                    </span>
                                    <span v-else>{{ Number(item.product.price).toLocaleString() }} ₽</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <!-- Сумма со скидкой -->
                                <p class="font-bold text-gray-900">
                                    {{ getItemTotal(item).toLocaleString() }} ₽
                                </p>
                                <!-- Старая сумма (если есть скидка) -->
                                <p v-if="item.product.discount" class="text-xs text-gray-400 line-through">
                                    {{ (item.product.price * item.quantity).toLocaleString() }} ₽
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500">
                            Итого:
                            <span class="font-bold text-gray-900 text-lg">
                                {{ Number(order.total_amount).toLocaleString() }} ₽
                            </span>
                            <span v-if="order.old_total" class="text-xs text-gray-400 line-through ml-2">
                                {{ Number(order.old_total).toLocaleString() }} ₽
                            </span>
                        </div>
                        <button v-if="order.status === 'pending' || order.status === 'processing'"
                            @click="cancelOrder(order)"
                            class="text-red-500 hover:text-red-600 text-sm font-medium transition">
                            Отменить заказ
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>
