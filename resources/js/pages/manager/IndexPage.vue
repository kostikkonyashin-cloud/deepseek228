<script setup>
import { ref } from 'vue'
import ProfileLayout from '../../layouts/ProfileLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    orders: {
        type: Object,
        default: () => ({ data: [] })
    }
});

// Состояние для открытых заказов
const expandedOrders = ref([])

const toggleOrder = (orderId) => {
    const index = expandedOrders.value.indexOf(orderId)
    if (index === -1) {
        expandedOrders.value.push(orderId)
    } else {
        expandedOrders.value.splice(index, 1)
    }
}

const isExpanded = (orderId) => {
    return expandedOrders.value.includes(orderId)
}

const updateStatus = (order, newStatus) => {
    router.patch(route('profile.management.update', order.id), {
        status: newStatus
    }, {
        preserveScroll: true
    })
}

const getStatusText = (status) => {
    const statuses = {
        pending: 'Ожидает оплаты',
        processing: 'В обработке',
        ready: 'Готов к выдаче',
        issued: 'Выдан',
        cancelled: 'Отклонен',
        completed: 'Выполнен'
    }
    return statuses[status] || status
}

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        ready: 'bg-purple-100 text-purple-800',
        issued: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        completed: 'bg-gray-100 text-gray-800'
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}

// Цена со скидкой
const getDiscountedPrice = (product) => {
    let price = product.price
    if (product.discount) {
        price = price * (1 - product.discount / 100)
    }
    return Math.round(price)
}

// Сумма позиции со скидкой
const getItemTotal = (item) => {
    const price = getDiscountedPrice(item.product)
    return price * item.quantity
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Управление заказами</h3>

                <!-- Пустое состояние -->
                <div v-if="!orders.data || orders.data.length === 0"
                    class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 text-lg">Заказов пока нет</p>
                </div>

                <div v-else v-for="order in orders.data" :key="order.id"
                    class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div @click="toggleOrder(order.id)"
                        class="p-6 cursor-pointer hover:bg-gray-50 transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <p class="text-sm font-medium text-gray-900">Заказ №{{ order.id }}</p>
                                <p class="text-sm text-gray-500">{{ order.ordered_at }}</p>
                            </div>
                            <div class="flex items-center gap-4 mt-2 flex-wrap">
                                <p class="text-sm text-gray-600">
                                    Покупатель: <span class="font-medium">{{ order.user?.full_name || 'Не указан'
                                    }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Email: <span class="font-medium">{{ order.user?.email || 'Не указан' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">{{
                                    Number(order.total_amount).toLocaleString() }} ₽</p>
                                <p class="text-xs text-gray-500">Товаров: {{ order.order_items?.length || 0 }}</p>
                            </div>

                            <select :value="order.status" @click.stop @change="updateStatus(order, $event.target.value)"
                                :class="['px-3 py-1 rounded-full text-sm font-medium border-0 cursor-pointer', getStatusColor(order.status)]">
                                <option value="processing">В обработке</option>
                                <option value="ready">Готов к выдаче</option>
                                <option value="issued">Выдан</option>
                                <option value="cancelled">Отклонен</option>
                            </select>

                            <svg class="w-5 h-5 text-gray-400 transition-transform"
                                :class="{ 'rotate-180': isExpanded(order.id) }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div v-if="isExpanded(order.id)" class="border-t border-gray-100 bg-gray-50 p-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Товары в заказе:</h4>
                        <div class="space-y-3">
                            <div v-for="item in order.order_items" :key="item.id"
                                class="bg-white rounded-lg p-4 flex flex-col sm:flex-row gap-4 shadow-sm">
                                <div
                                    class="w-20 h-20 bg-gray-100 rounded-lg shrink-0 flex items-center justify-center overflow-hidden">
                                    <img v-if="item.product?.images?.[0]" :src="item.product.images[0]"
                                        :alt="item.product?.name || 'Товар'" class="w-full h-full object-cover">
                                    <img v-else-if="item.product?.image_url" :src="item.product.image_url"
                                        :alt="item.product?.name || 'Товар'" class="w-full h-full object-cover">
                                    <svg v-else class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-800">
                                        {{ item.product?.brand?.name || '' }} {{ item.product?.name || 'Товар' }}
                                    </h5>

                                    <p class="text-sm text-gray-500 mt-1"
                                        v-if="item.color?.name || item.size?.name || item.material?.name">
                                        {{ item.color?.name || '' }} {{ item.size?.name || '' }} {{ item.material?.name
                                            || '' }}
                                    </p>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ item.quantity }} ×
                                        <span v-if="item.product?.discount">
                                            <span class="line-through text-gray-400 mr-1">{{
                                                Number(item.product.price).toLocaleString() }} ₽</span>
                                            <span class="text-green-600">{{
                                                getDiscountedPrice(item.product).toLocaleString() }} ₽</span>
                                        </span>
                                        <span v-else>{{ Number(item.product.price).toLocaleString() }} ₽</span>
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold text-gray-900">
                                        {{ getItemTotal(item).toLocaleString() }} ₽
                                    </p>
                                    <p v-if="item.product?.discount" class="text-xs text-gray-400 line-through">
                                        {{ (item.product.price * item.quantity).toLocaleString() }} ₽
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 text-right">
                            <p class="text-sm text-gray-500">
                                Общая сумма заказа:
                                <span class="text-xl font-bold text-gray-900 ml-2">
                                    {{ Number(order.total_amount).toLocaleString() }} ₽
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}
</style>
