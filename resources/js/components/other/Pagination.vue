<template>
    <nav class="flex justify-center mt-8">
        <div class="hidden sm:flex gap-1">
            <Link v-if="prevLink" :href="prevLink.url || '#'"
                class="min-w-9 h-9 flex items-center justify-center px-3 rounded-md text-sm font-medium transition-colors text-gray-700 bg-white border border-gray-300 hover:bg-gray-100">
                ← Назад
            </Link>

            <Link v-for="link in pageLinks" :key="link.label" :href="link.url || '#'" :class="[
                'min-w-9 h-9 flex items-center justify-center px-3 rounded-md text-sm font-medium transition-colors',
                link.active
                    ? 'bg-gray-900 text-white cursor-default'
                    : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-100'
            ]" v-html="link.label" />

            <Link v-if="nextLink" :href="nextLink.url || '#'"
                class="min-w-9 h-9 flex items-center justify-center px-3 rounded-md text-sm font-medium transition-colors text-gray-700 bg-white border border-gray-300 hover:bg-gray-100">
                Вперед →
            </Link>
        </div>

        <div class="flex sm:hidden gap-3">
            <Link v-if="prevLink" :href="prevLink.url || '#'"
                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-100 transition">
                ← Назад
            </Link>
            <span class="px-4 py-2 text-gray-500 text-sm">
                {{ currentPage }} / {{ lastPage }}
            </span>
            <Link v-if="nextLink" :href="nextLink.url || '#'"
                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-100 transition">
                Вперед →
            </Link>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    links: {
        type: Array,
        default: () => []
    }
})

const prevLink = computed(() => {
    return props.links.find(link => {
        const label = link.label
        return label === '&laquo; Previous' || label === '‹' || label === 'Previous' || label === 'Предыдущая'
    })
})

const nextLink = computed(() => {
    return props.links.find(link => {
        const label = link.label
        return label === 'Next &raquo;' || label === '›' || label === 'Next' || label === 'Следующая'
    })
})

const pageLinks = computed(() => {
    return props.links.filter(link => {
        const label = link.label
        const isPrevNext = label === '&laquo; Previous' || label === '‹' || label === 'Previous' ||
            label === 'Предыдущая' || label === 'Next &raquo;' || label === '›' ||
            label === 'Next' || label === 'Следующая'
        return !isPrevNext
    })
})

const currentPage = computed(() => {
    const activeLink = props.links.find(link => link.active)
    if (activeLink && !isNaN(parseInt(activeLink.label))) {
        return parseInt(activeLink.label)
    }
    return 1
})

const lastPage = computed(() => {
    const pageNumbers = pageLinks.value.filter(link => !isNaN(parseInt(link.label)))
    if (pageNumbers.length > 0) {
        const lastNum = parseInt(pageNumbers[pageNumbers.length - 1].label)
        return lastNum
    }
    return 1
})
</script>
