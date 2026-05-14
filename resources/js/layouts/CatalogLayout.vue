<script setup>
import { router } from '@inertiajs/vue3'
import AppBaseLayout from './AppBaseLayout.vue'
import { computed } from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Название'
    },
    category_types: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    slug: {
        type: String,
        default: null
    }
})

const activeCategoryType = computed(() => props.filters?.category_type || null)

const selectCategoryType = (categorySlug) => {
    const currentFilters = { ...props.filters }

    if (activeCategoryType.value === categorySlug) {
        delete currentFilters.category_type
    } else {
        currentFilters.category_type = categorySlug
    }

    router.get(route('product.index', { category: props.slug }), currentFilters, {
        preserveState: true,
        preserveScroll: true,
        only: ['products', 'filters']
    })
}
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 w-full">
            <h1 class="text-2xl font-semibold" :class="[
                category_types.length > 0 ? 'mb-3' : 'mb-6'
            ]">{{ title }}</h1>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 mb-4"
                v-if="category_types.length > 0">
                <button type="button" @click="selectCategoryType(type.slug)" :class="[
                    'border rounded-lg p-2 sm:p-3 cursor-pointer transition-all duration-200 text-sm sm:text-base',
                    activeCategoryType === type.slug
                        ? 'bg-gray-800 text-white border-gray-800'
                        : 'bg-white border-gray-200 hover:bg-gray-800 hover:text-white'
                ]" v-for="type in category_types" :key="type.id">
                    {{ type.name }}
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="lg:w-1/4">
                    <slot name="filters" />
                </aside>

                <div class="lg:w-3/4">
                    <slot name="products" />

                    <div class="mt-8">
                        <slot name="pagination" />
                    </div>
                </div>
            </div>
        </div>
    </AppBaseLayout>
</template>
