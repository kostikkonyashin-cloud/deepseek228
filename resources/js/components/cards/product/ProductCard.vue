<script setup>
import { ref, onMounted, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps(['product']);

const currentImageIndex = ref(0)
const images = ref([])

// Получаем первый доступный цвет, размер и материал
const defaultColorId = computed(() => {
    return props.product.colors && props.product.colors.length > 0
        ? props.product.colors[0].id
        : null
})

const defaultSizeId = computed(() => {
    return props.product.sizes && props.product.sizes.length > 0
        ? props.product.sizes[0].id
        : null
})

const defaultMaterialId = computed(() => {
    return props.product.materials && props.product.materials.length > 0
        ? props.product.materials[0].id
        : null
})

onMounted(() => {
    if (props.product.product_images && props.product.product_images.length > 0) {
        images.value = props.product.product_images.map(img => img.image_path)
    }
})

const nextImage = (e) => {
    e.stopPropagation()
    e.preventDefault()
    if (images.value.length > 0) {
        currentImageIndex.value = (currentImageIndex.value + 1) % images.value.length
    }
}

const prevImage = (e) => {
    e.stopPropagation()
    e.preventDefault()
    if (images.value.length > 0) {
        currentImageIndex.value = (currentImageIndex.value - 1 + images.value.length) % images.value.length
    }
}

const addToCart = (e, product) => {
    e.stopPropagation()
    e.preventDefault()

    const cartData = {
        product_id: product.id,
        quantity: 1
    }

    if (defaultColorId.value) {
        cartData.color_id = defaultColorId.value
    }
    if (defaultSizeId.value) {
        cartData.size_id = defaultSizeId.value
    }
    if (defaultMaterialId.value) {
        cartData.material_id = defaultMaterialId.value
    }

    router.post(route('product.store'), cartData, {
        preserveScroll: true,
        onSuccess: (message) => {
            console.log('Успех', message)
        },
        onError: (message) => {
            console.error('Ошибка добавления в корзину:', message)
        }
    })
}
</script>

<template>
    <div
        class="bg-white rounded-2xl border border-gray-100 p-3 flex flex-col h-full transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <Link :href="route('product.show', { product: product.slug, category: product.category.slug })"
            class="block flex-1">

            <!-- Слайдер изображений -->
            <div
                class="relative w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center mb-3 shrink-0 overflow-hidden group">
                <!-- Изображение -->
                <img v-if="images.length > 0" :src="images[currentImageIndex]" :alt="product.name"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                <!-- Заглушка -->
                <svg v-else class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>

                <!-- Кнопки навигации -->
                <button v-if="images.length > 1" @click="prevImage"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button v-if="images.length > 1" @click="nextImage"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Индикаторы -->
                <div v-if="images.length > 1" class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                    <button v-for="(_, idx) in images" :key="idx"
                        @click="(e) => { e.stopPropagation(); e.preventDefault(); currentImageIndex = idx }" :class="[
                            'w-1.5 h-1.5 rounded-full transition-all duration-200',
                            currentImageIndex === idx ? 'bg-white w-3' : 'bg-white/50'
                        ]"></button>
                </div>

                <!-- Бейдж скидки -->
                <div v-if="product.discount"
                    class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-md z-10">
                    -{{ product.discount }}%
                </div>
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <div :class="product.is_available ? 'bg-green-500' : 'bg-red-500'" class="size-1.5 rounded-full">
                    </div>
                    <span class="text-sm text-gray-900">
                        {{ product.is_available ? 'В наличии' : 'Нет в наличии' }}
                    </span>
                </div>
                <h3 class="text-base font-semibold text-gray-800 line-clamp-2 mb-1">
                    {{ product.brand?.name }} {{ product.name }}
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    {{ product.description }}
                </p>
            </div>
        </Link>

        <button @click="(e) => addToCart(e, product)"
            class="w-full mt-3 py-2 px-3 bg-gray-50 hover:bg-gray-800 rounded-lg transition-all duration-200 flex items-center justify-between group/btn cursor-pointer"
            :disabled="!product.is_available">
            <div class="flex flex-col justify-center items-start min-h-9">
                <span class="font-bold text-gray-900 group-hover/btn:text-white transition-colors">
                    {{ Math.round(product.price * (1 - (product.discount || 0) / 100)).toLocaleString() }} ₽
                </span>
                <span v-if="product.discount"
                    class="text-[12px] text-gray-400 line-through group-hover/btn:text-gray-300 transition-colors">
                    {{ product.price.toLocaleString() }} ₽
                </span>
            </div>
            <i class="fa-solid fa-cart-shopping group-hover/btn:text-white transition-colors"></i>
        </button>
    </div>
</template>
