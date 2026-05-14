<template>
    <div class="bg-white rounded-xl shadow-sm p-5 sticky top-20">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Фильтры</h3>
        <form @submit.prevent="applyFilters" class="space-y-6">
            <!-- Категории с поиском и сворачиванием -->
            <div v-if="categories && categories.length">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-900">Категории</h4>
                    <button type="button" @click="toggleCategories"
                        class="text-xs text-gray-500 hover:text-purple-600 transition">
                        {{ showAllCategories ? 'Скрыть' : 'Показать все' }}
                    </button>
                </div>

                <!-- Поиск по категориям -->
                <div class="relative mb-3">
                    <input type="text" v-model="categorySearch" placeholder="Поиск категории..."
                        class="w-full px-3 py-2 pl-8 border border-gray-200 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    <svg class="absolute left-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="space-y-2 max-h-60 overflow-y-auto">
                    <label v-for="category in filteredCategories" :key="category.id"
                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" :value="category.slug" v-model="form.categories"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <span class="text-sm text-gray-700">{{ category.name }}</span>
                    </label>
                    <div v-if="filteredCategories.length === 0" class="text-center py-4 text-gray-400 text-sm">
                        Категории не найдены
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-3">Цена</h4>
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 block mb-1">От</label>
                        <input type="number" v-model="form.price_min" :min="min_price"
                            :max="form.price_max || max_price"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 block mb-1">До</label>
                        <input type="number" v-model="form.price_max" :min="form.price_min || min_price"
                            :max="max_price"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-400">
                    Диапазон: {{ min_price }} ₽ - {{ max_price }} ₽
                </div>
            </div>

            <div v-if="colors && colors.length">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-900">Цвета</h4>
                    <button type="button" @click="toggleColors"
                        class="text-xs text-gray-500 hover:text-purple-600 transition">
                        {{ showAllColors ? 'Скрыть' : 'Показать все' }}
                    </button>
                </div>
                <div class="space-y-2 max-h-40 overflow-y-auto" :class="!showAllColors && 'max-h-32'">
                    <label v-for="color in (showAllColors ? colors : colors.slice(0, 5))" :key="color.id"
                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" :value="color.slug" v-model="form.colors"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <span class="w-4 h-4 rounded-full border border-gray-300"
                            :style="{ backgroundColor: color.code }"></span>
                        <span class="text-sm text-gray-700">{{ color.name }}</span>
                    </label>
                </div>
            </div>

            <div v-if="sizes && sizes.length">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-900">Размеры</h4>
                    <button type="button" @click="toggleSizes"
                        class="text-xs text-gray-500 hover:text-purple-600 transition">
                        {{ showAllSizes ? 'Скрыть' : 'Показать все' }}
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto" :class="!showAllSizes && 'max-h-32'">
                    <label v-for="size in (showAllSizes ? sizes : sizes.slice(0, 6))" :key="size.id"
                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" :value="size.slug" v-model="form.sizes"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <span class="text-sm text-gray-700">{{ size.name }}</span>
                    </label>
                </div>
            </div>

            <div v-if="materials && materials.length">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-900">Материалы</h4>
                    <button type="button" @click="toggleMaterials"
                        class="text-xs text-gray-500 hover:text-purple-600 transition">
                        {{ showAllMaterials ? 'Скрыть' : 'Показать все' }}
                    </button>
                </div>
                <div class="space-y-2 max-h-40 overflow-y-auto" :class="!showAllMaterials && 'max-h-32'">
                    <label v-for="material in (showAllMaterials ? materials : materials.slice(0, 5))" :key="material.id"
                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded transition">
                        <input type="checkbox" :value="material.slug" v-model="form.materials"
                            class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <span class="text-sm text-gray-700">{{ material.name }}</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" :disabled="form.processing"
                    class="flex-1 bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-800 transition disabled:opacity-50 cursor-pointer">
                    {{ form.processing ? 'Загрузка...' : 'Применить' }}
                </button>
                <button type="button" @click="resetFilters"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                    Сбросить
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { computed, watch, ref } from 'vue'

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    sizes: {
        type: Array,
        default: () => []
    },
    materials: {
        type: Array,
        default: () => []
    },
    colors: {
        type: Array,
        default: () => []
    },
    min_price: {
        type: Number,
        default: 0
    },
    max_price: {
        type: Number,
        default: 100000
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    route: {
        type: Object,
    }
})

// Состояния для сворачивания
const showAllCategories = ref(false)
const showAllColors = ref(false)
const showAllSizes = ref(false)
const showAllMaterials = ref(false)

// Поиск по категориям
const categorySearch = ref('')

const filteredCategories = computed(() => {
    if (!categorySearch.value) {
        return showAllCategories.value ? props.categories : props.categories.slice(0, 7)
    }
    return props.categories.filter(cat =>
        cat.name.toLowerCase().includes(categorySearch.value.toLowerCase())
    )
})

const toggleCategories = () => {
    showAllCategories.value = !showAllCategories.value
    categorySearch.value = ''
}

const toggleColors = () => showAllColors.value = !showAllColors.value
const toggleSizes = () => showAllSizes.value = !showAllSizes.value
const toggleMaterials = () => showAllMaterials.value = !showAllMaterials.value

const form = useForm({
    categories: props.filters.categories || [],
    price_min: props.filters.price_min || null,
    price_max: props.filters.price_max || null,
    colors: props.filters.colors || [],
    sizes: props.filters.sizes || [],
    materials: props.filters.materials || [],
})

const applyFilters = () => {
    const params = {}

    if (form.categories.length) params.categories = form.categories
    if (form.price_min) params.price_min = form.price_min
    if (form.price_max) params.price_max = form.price_max
    if (form.colors.length) params.colors = form.colors
    if (form.sizes.length) params.sizes = form.sizes
    if (form.materials.length) params.materials = form.materials

    router.get(route(props.route.href, props.route.params), params, {
        preserveState: true,
        preserveScroll: true,
        only: ['products', 'filters']
    })
}

const resetFilters = () => {
    form.categories = []
    form.price_min = null
    form.price_max = null
    form.colors = []
    form.sizes = []
    form.materials = []

    router.get(route(props.route.href, props.route.params), {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['products', 'filters']
    })
}

watch(() => props.filters, (newFilters) => {
    form.categories = newFilters.categories || []
    form.price_min = newFilters.price_min || null
    form.price_max = newFilters.price_max || null
    form.colors = newFilters.colors || []
    form.sizes = newFilters.sizes || []
    form.materials = newFilters.materials || []
}, { deep: true })
</script>
