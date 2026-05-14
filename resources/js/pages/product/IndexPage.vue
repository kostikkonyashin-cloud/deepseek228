<script setup>
const props = defineProps(['products', 'category', 'category_types', 'sizes', 'colors', 'materials', 'min_price', 'max_price', 'filters']);
import CatalogLayout from '../../layouts/CatalogLayout.vue';
import ProductCard from '../../components/cards/product/ProductCard.vue';
import ProductFilterForm from '../../components/forms/filter/product/ProductFilterForm.vue';
import Pagination from '../../components/other/Pagination.vue'
import { computed, watch } from 'vue';

const route = computed(() => {
    return {
        href: 'product.index',
        params: {
            category: props.category.slug
        }
    }
});
</script>
<template>
    <CatalogLayout :title="category.name" :category_types="category_types" :slug="category.slug" :filters="filters">
        <template #filters>
            <ProductFilterForm :route="route" :materials="materials" :max_price="max_price" :min_price="min_price"
                :colors="colors" :sizes="sizes" :category="category.slug" />
        </template>

        <template #products>
            <div v-if="products.data && products.data.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                </div>
            </div>

            <div v-else class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 text-lg">Товары не найдены</p>
                <p class="text-gray-400 text-sm mt-2">Попробуйте изменить параметры фильтрации</p>
            </div>
        </template>
        <template #pagination>
            <Pagination :links="products.links" />
        </template>
    </CatalogLayout>
</template>
