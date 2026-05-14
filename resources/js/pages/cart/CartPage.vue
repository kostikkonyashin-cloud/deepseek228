<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Корзина</h1>
            <div v-if="cart_products.length === 0" class="text-center py-16 bg-white rounded-2xl shadow-sm">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 21v-8" />
                </svg>
                <p class="text-gray-500 text-lg mb-4">Ваша корзина пуста</p>
                <Link :href="route('index')"
                    class="inline-block bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                Перейти к покупкам
                </Link>
            </div>

            <div v-else class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="selectAll"
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Выбрать все</span>
                        </label>
                        <button @click="removeSelected" v-if="selectedItems.length > 0"
                            class="text-sm text-red-500 hover:text-red-600 transition">
                            Удалить выбранные ({{ selectedItems.length }})
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div v-for="item in cart_products" :key="item.id"
                            class="bg-white rounded-xl shadow-sm p-4 flex flex-col sm:flex-row gap-4 transition-all hover:shadow-md">
                            <div class="flex items-start">
                                <input type="checkbox" v-model="selectedItems" :value="item.id"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mt-1">
                            </div>

                            <div class="w-24 h-24 bg-gray-100 rounded-lg shrink-0 overflow-hidden">
                                <img :src="item.product.images?.[0] || 'https://placehold.co/100x100/eee/ccc?text=No+Image'"
                                    :alt="item.product.name" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1 hover:text-blue-600 transition">
                                    <Link :href="route('product.show', {
                                        product: item.product.slug,
                                        category: item.product.category?.slug
                                    })">
                                    {{ item.product.name }}
                                    </Link>
                                </h3>

                                <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-3">
                                    <span v-if="item.color" class="flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-full"
                                            :style="{ backgroundColor: item.color.code }"></span>
                                        {{ item.color.name }}
                                    </span>
                                    <span v-if="item.size">Размер: {{ item.size.name }}</span>
                                    <span v-if="item.material">Материал: {{ item.material.name }}</span>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button @click="updateQuantity(item, item.quantity - 1)"
                                            class="px-3 py-1.5 hover:bg-gray-100 transition-colors disabled:opacity-40"
                                            :disabled="item.quantity <= 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-10 text-center text-sm font-medium">{{ item.quantity }}</span>
                                        <button @click="updateQuantity(item, item.quantity + 1)"
                                            class="px-3 py-1.5 hover:bg-gray-100 transition-colors disabled:opacity-40"
                                            :disabled="item.quantity >= (item.product.max_cart_quantity || 99)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">
                                            {{ getItemFinalPrice(item).toLocaleString() }} ₽
                                        </p>
                                        <p v-if="item.product.discount" class="text-xs text-gray-400 line-through">
                                            {{ (item.product.price * item.quantity).toLocaleString() }} ₽
                                        </p>
                                    </div>

                                    <button @click="removeItem(item)"
                                        class="text-red-400 hover:text-red-600 transition cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-20">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Итого</h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Товары ({{ totalSelectedItems }} шт.)</span>
                                <span class="text-gray-900">{{ totalSelectedPrice.toLocaleString() }} ₽</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Скидка</span>
                                <span class="text-red-500">- {{ totalSelectedDiscount.toLocaleString() }} ₽</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>К оплате</span>
                                    <span class="text-gray-900">{{ totalSelectedFinalPrice.toLocaleString() }} ₽</span>
                                </div>
                            </div>
                        </div>

                        <!-- Обычная HTML-форма вместо Inertia router.post -->
                        <form :action="route('order.store')" method="POST">
                            <input type="hidden" name="_token" :value="$page.props.csrf_token">
                            <input v-for="id in selectedItems" :key="id" type="hidden" name="cart_item_ids[]"
                                :value="id">
                            <button type="submit" :disabled="selectedItems.length === 0"
                                class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Перейти к оплате
                            </button>
                        </form>

                        <button @click="clearCart"
                            class="w-full text-red-500 text-sm mt-4 hover:underline cursor-pointer">
                            Очистить корзину
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppBaseLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppBaseLayout from '@/Layouts/AppBaseLayout.vue'

const props = defineProps({
    cart_products: {
        type: Array,
        required: true,
        default: () => []
    }
})

const selectedItems = ref(props.cart_products.map(item => item.id))

const selectAll = computed({
    get: () => selectedItems.value.length === props.cart_products.length && props.cart_products.length > 0,
    set: (value) => {
        if (value) {
            selectedItems.value = props.cart_products.map(item => item.id)
        } else {
            selectedItems.value = []
        }
    }
})

const selectedCartItems = computed(() => {
    return props.cart_products.filter(item => selectedItems.value.includes(item.id))
})

const totalSelectedItems = computed(() => {
    return selectedCartItems.value.reduce((sum, item) => sum + item.quantity, 0)
})

const totalSelectedPrice = computed(() => {
    return selectedCartItems.value.reduce((sum, item) => {
        return sum + (item.product.price * item.quantity)
    }, 0)
})

const totalSelectedDiscount = computed(() => {
    return selectedCartItems.value.reduce((sum, item) => {
        if (item.product.discount) {
            const discountAmount = item.product.price * (item.product.discount / 100)
            return sum + (discountAmount * item.quantity)
        }
        return sum
    }, 0)
})

const totalSelectedFinalPrice = computed(() => {
    return selectedCartItems.value.reduce((sum, item) => {
        let price = item.product.price
        if (item.product.discount) {
            price = price * (1 - item.product.discount / 100)
        }
        return sum + (price * item.quantity)
    }, 0)
})

const getItemFinalPrice = (item) => {
    let price = item.product.price
    if (item.product.discount) {
        price = price * (1 - item.product.discount / 100)
    }
    return Math.round(price * item.quantity)
}

const updateQuantity = (item, quantity) => {
    if (quantity < 1) return

    const params = { quantity }

    if (item.color_id) params.color_id = item.color_id
    if (item.size_id) params.size_id = item.size_id
    if (item.material_id) params.material_id = item.material_id

    router.patch(route('product.update', item.product.slug), params, {
        preserveScroll: true,
        preserveState: true
    })
}

const removeItem = (item) => {
    if (confirm('Удалить товар из корзины?')) {
        const params = {}

        if (item.color_id) params.color_id = item.color_id
        if (item.size_id) params.size_id = item.size_id
        if (item.material_id) params.material_id = item.material_id

        router.delete(route('product.destroy', item.product.slug), {
            data: params,
            preserveScroll: true
        })
    }
}

const removeSelected = () => {
    if (confirm(`Удалить ${selectedItems.value.length} товар(ов) из корзины?`)) {
        router.post(route('cart.remove-selected'), { ids: selectedItems.value }, {
            preserveScroll: true,
            onSuccess: () => {
                selectedItems.value = []
            }
        })
    }
}

const clearCart = () => {
    if (confirm('Очистить всю корзину?')) {
        router.delete(route('cart.destroy'), {
            preserveScroll: true
        })
    }
}
</script>
