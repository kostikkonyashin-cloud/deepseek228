<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppBaseLayout from '@/Layouts/AppBaseLayout.vue'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const selectedImage = ref(0)
const selectedColor = ref(props.product.colors?.[0]?.id || null)
const selectedSize = ref(props.product.sizes?.[0]?.id || null)
const quantity = ref(1)

const form = useForm({
    quantity: 1,
    product_id: props.product.id,
    color_id: null,
    size_id: null
})

watch(selectedColor, (newVal) => {
    form.color_id = newVal
})

watch(selectedSize, (newVal) => {
    form.size_id = newVal
})

const finalPrice = computed(() => {
    if (props.product.discount) {
        return Math.round(props.product.price * (1 - props.product.discount / 100))
    }
    return props.product.price
})

const specifications = computed(() => {
    return props.product.specifications || {
        'Производитель': props.product.brand?.name || '—',
        'Категория': props.product.category?.name || '—',
        'Модель': props.product.name
    }
})

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--
        form.quantity = quantity.value
    }
}

const incrementQuantity = () => {
    if (quantity.value < (props.product.product_count || 99)) {
        quantity.value++
        form.quantity = quantity.value
    }
}

const addToCart = () => {
    form.quantity = quantity.value
    form.post(route('product.store'), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => {
            console.error('Ошибка:', errors)
        }
    })
}

const characteristics = computed(() => {
    const parts = []

    if (selectedColor.value && props.product.colors) {
        const color = props.product.colors.find(c => c.id === selectedColor.value)
        if (color) parts.push(color.name)
    }

    if (selectedSize.value && props.product.sizes) {
        const size = props.product.sizes.find(s => s.id === selectedSize.value)
        if (size) parts.push(size.name)
    }

    if (props.product.materials && props.product.materials.length) {
        const materialNames = props.product.materials.map(m => m.name).join(', ')
        parts.push(materialNames)
    }

    return parts.join(' • ')
})
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Левая колонка - изображения -->
                <div class="lg:w-1/2">
                    <div class="sticky top-20">
                        <div class="relative bg-gray-100 rounded-2xl overflow-hidden aspect-square mb-4">
                            <img v-if="product.product_images && product.product_images.length"
                                :src="product.product_images[selectedImage]?.image_path || 'https://placehold.co/600x600/eee/ccc?text=No+Image'"
                                :alt="product.name" class="w-full h-full object-cover">
                            <img v-else src="https://placehold.co/600x600/eee/ccc?text=No+Image" :alt="product.name"
                                class="w-full h-full object-cover">
                            <div v-if="product.discount"
                                class="absolute top-3 left-3 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-lg">
                                -{{ product.discount }}%
                            </div>
                        </div>

                        <div v-if="product.product_images && product.product_images.length > 1"
                            class="grid grid-cols-4 gap-3">
                            <div v-for="(image, index) in product.product_images" :key="index"
                                class="bg-gray-100 rounded-lg overflow-hidden aspect-square cursor-pointer border-2 transition-all"
                                :class="selectedImage === index ? 'border-blue-500' : 'border-transparent hover:border-gray-300'"
                                @click="selectedImage = index">
                                <img :src="image.image_path" :alt="`${product.name} image ${index + 1}`"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Правая колонка - информация -->
                <div class="lg:w-1/2">
                    <div class="flex items-center gap-2 mb-3">
                        <div :class="product.is_available ? 'bg-green-500' : 'bg-red-500'" class="w-2 h-2 rounded-full">
                        </div>
                        <span class="text-sm" :class="product.is_available ? 'text-green-600' : 'text-red-600'">
                            {{ product.is_available ? 'В наличии' : 'Нет в наличии' }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-3">
                        {{ product.name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-3 mb-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                            {{ product.category?.name }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z" />
                            </svg>
                            {{ product.brand?.name }}
                        </span>
                        <span v-if="characteristics" class="text-gray-400">•</span>
                        <span class="text-gray-500">{{ characteristics }}</span>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-bold text-gray-900">
                                {{ finalPrice.toLocaleString() }} ₽
                            </span>
                            <span v-if="product.discount" class="text-lg text-gray-400 line-through">
                                {{ product.price.toLocaleString() }} ₽
                            </span>
                        </div>
                        <div v-if="product.discount" class="text-sm text-green-600 mt-1">
                            Экономия: {{ (product.price - finalPrice).toLocaleString() }} ₽
                        </div>
                    </div>

                    <!-- Выбор цвета -->
                    <div class="mb-6" v-if="product.colors && product.colors.length">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Цвет:</h3>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="color in product.colors" :key="color.id" @click="selectedColor = color.id"
                                class="w-8 h-8 rounded-full border-2 transition-all"
                                :style="{ backgroundColor: color.code }" :class="selectedColor === color.id
                                    ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-900'
                                    : 'border-gray-300 hover:border-gray-400'" :title="color.name">
                            </button>
                        </div>
                    </div>

                    <!-- Выбор размера -->
                    <div class="mb-6" v-if="product.sizes && product.sizes.length">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Размер:</h3>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="size in product.sizes" :key="size.id" @click="selectedSize = size.id"
                                class="px-4 py-2 border rounded-lg text-sm transition-all" :class="selectedSize === size.id
                                    ? 'bg-gray-900 text-white border-gray-900'
                                    : 'border-gray-300 hover:border-gray-400'">
                                {{ size.name }}
                            </button>
                        </div>
                    </div>

                    <!-- Количество и кнопка -->
                    <div class="flex gap-3 mb-8">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button @click="decrementQuantity"
                                class="px-3 py-2 hover:bg-gray-100 transition-colors disabled:opacity-50"
                                :disabled="quantity <= 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="w-12 text-center font-medium">{{ quantity }}</span>
                            <button @click="incrementQuantity"
                                class="px-3 py-2 hover:bg-gray-100 transition-colors disabled:opacity-50"
                                :disabled="quantity >= (product.product_count || 99)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>

                        <button @click="addToCart" :disabled="!product.is_available"
                            class="cursor-pointer flex-1 bg-gray-900 text-white py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 21v-8" />
                            </svg>
                            В корзину
                        </button>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Описание</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ product.description }}
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Характеристики</h3>
                        <div class="space-y-2">
                            <div v-for="(value, key) in specifications" :key="key"
                                class="flex py-2 border-b border-gray-100">
                                <span class="w-1/3 text-sm text-gray-500">{{ key }}</span>
                                <span class="w-2/3 text-sm text-gray-900">{{ value }}</span>
                            </div>
                            <div v-if="product.materials && product.materials.length" class="flex py-2 border-b border-gray-100">
                                <span class="w-1/3 text-sm text-gray-500">Материалы</span>
                                <span class="w-2/3 text-sm text-gray-900">{{ product.materials.map(m => m.name).join(', ') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppBaseLayout>
</template>
