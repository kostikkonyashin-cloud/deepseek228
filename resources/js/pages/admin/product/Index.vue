<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 })
    },
    categories: {
        type: Array,
        default: () => []
    },
    brands: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search || '')
const categoryFilter = ref(props.filters.category || '')
const brandFilter = ref(props.filters.brand || '')
const statusFilter = ref(props.filters.status || '')
const searchTimeout = ref(null)
const isLoadingExport = ref(false)
const exportErrors = ref([])

const paginationLinks = computed(() => props.products.links || [])

const applyFilters = () => {
    router.get(route('admin.product.index'), {
        search: search.value,
        category: categoryFilter.value,
        brand: brandFilter.value,
        status: statusFilter.value
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

const onSearchInput = () => {
    if (searchTimeout.value) clearTimeout(searchTimeout.value)
    searchTimeout.value = setTimeout(() => {
        applyFilters()
    }, 500)
}

const resetFilters = () => {
    search.value = ''
    categoryFilter.value = ''
    brandFilter.value = ''
    statusFilter.value = ''
    applyFilters()
}

const editProduct = (product) => {
    router.get(route('admin.product.edit', product.id))
}

const deleteProduct = (product) => {
    if (confirm(`Вы уверены, что хотите удалить товар "${product.name}"?`)) {
        router.delete(route('admin.product.destroy', product.id), {
            preserveScroll: true
        })
    }
}

const toggleAvailability = (product) => {
    router.post(route('admin.product.toggle-availability', product.id), {}, {
        preserveScroll: true
    })
}

const getStatusBadgeClass = (isAvailable) => {
    return isAvailable
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800'
}

const getStatusText = (isAvailable) => {
    return isAvailable ? 'Доступен' : 'Недоступен'
}

const startDate = ref('')
const endDate = ref('')

// Очистка ошибок
const clearErrors = () => {
    exportErrors.value = []
}

// Валидация дат
const validateDates = () => {
    const errors = []
    
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value)
        const end = new Date(endDate.value)
        
        if (start > end) {
            errors.push('Дата "От" не может быть позже даты "До"')
        }
    }
    
    return errors
}

// Экспорт заказов
const handleExportOrders = async () => {
    clearErrors()
    
    // Валидация дат
    const validationErrors = validateDates()
    if (validationErrors.length > 0) {
        exportErrors.value = validationErrors
        return
    }
    
    isLoadingExport.value = true
    
    try {
        let url = '/admin/export-orders?'
        if (startDate.value) url += `start_date=${startDate.value}&`
        if (endDate.value) url += `end_date=${endDate.value}`
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        
        if (response.status === 422) {
            const errorData = await response.json()
            exportErrors.value = errorData.errors || ['Ошибка валидации данных']
            return
        }
        
        if (response.status === 404) {
            const errorData = await response.json()
            exportErrors.value = errorData.errors || ['Данные не найдены']
            return
        }
        
        if (!response.ok) {
            throw new Error('Ошибка при экспорте')
        }
        
        // Скачивание файла
        const blob = await response.blob()
        const url_ = window.URL.createObjectURL(blob)
        const a = document.createElement('a')
        a.href = url_
        a.download = response.headers.get('Content-Disposition')?.split('filename=')[1]?.replace(/"/g, '') || 'orders_report.xlsx'
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)
        window.URL.revokeObjectURL(url_)
        
    } catch (error) {
        console.error('Export error:', error)
        exportErrors.value = ['Произошла ошибка при экспорте. Попробуйте позже.']
    } finally {
        isLoadingExport.value = false
    }
}

// Экспорт остатков
const handleExportInventory = async () => {
    clearErrors()
    isLoadingExport.value = true
    
    try {
        const response = await fetch('/admin/export-inventory', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        
        if (response.status === 404) {
            const errorData = await response.json()
            exportErrors.value = errorData.errors || ['Данные не найдены']
            return
        }
        
        if (!response.ok) {
            throw new Error('Ошибка при экспорте')
        }
        
        const blob = await response.blob()
        const url = window.URL.createObjectURL(blob)
        const a = document.createElement('a')
        a.href = url
        a.download = response.headers.get('Content-Disposition')?.split('filename=')[1]?.replace(/"/g, '') || 'inventory_report.xlsx'
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)
        window.URL.revokeObjectURL(url)
        
    } catch (error) {
        console.error('Export error:', error)
        exportErrors.value = ['Произошла ошибка при экспорте. Попробуйте позже.']
    } finally {
        isLoadingExport.value = false
    }
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <!-- Заголовок и кнопка добавления -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-semibold text-gray-800">Управление товарами</h3>
                    <Link :href="route('admin.product.create')"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить товар
                    </Link>
                </div>

                <!-- Фильтры -->
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <input type="text" v-model="search" @input="onSearchInput" placeholder="Поиск по названию..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="w-full md:w-48">
                        <select v-model="categoryFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все категории</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>
                    <div class="w-full md:w-48">
                        <select v-model="brandFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все бренды</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.slug">
                                {{ brand.name }}
                            </option>
                        </select>
                    </div>
                    <div class="w-full md:w-40">
                        <select v-model="statusFilter" @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Все статусы</option>
                            <option value="available">В наличии</option>
                            <option value="unavailable">Нет в наличии</option>
                            <option value="discounted">Со скидкой</option>
                        </select>
                    </div>
                    <button @click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Сбросить
                    </button>
                </div>

                <!-- Таблица товаров -->
                <div v-if="products.data && products.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">ID</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Изображение</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Название</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Категория</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Бренд</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Цена</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Остаток</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Статус</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-500">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products.data" :key="product.id"
                                class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-500">{{ product.id }}</td>
                                <td class="py-3 px-4">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name"
                                            class="w-full h-full object-cover">
                                        <svg v-else class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-gray-800">{{ product.name }}</div>
                                    <div class="text-xs text-gray-400 truncate max-w-xs">{{ product.slug }}</div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ product.category?.name || '-' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ product.brand?.name || '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-gray-800">{{ product.price.toLocaleString() }}
                                        ₽</div>
                                    <div v-if="product.discount" class="text-xs text-red-500">-{{ product.discount }}%
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ product.product_count }} шт.</td>
                                <td class="py-3 px-4">
                                    <span
                                        :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusBadgeClass(product.is_available)]">
                                        {{ getStatusText(product.is_available) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editProduct(product)"
                                            class="text-blue-500 hover:text-blue-600 transition" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="toggleAvailability(product)"
                                            :class="product.is_available ? 'text-yellow-500 hover:text-yellow-600' : 'text-green-500 hover:text-green-600'"
                                            :title="product.is_available ? 'Снять с продажи' : 'Вернуть в продажу'">
                                            <svg v-if="product.is_available" class="w-5 h-5" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteProduct(product)"
                                            class="text-red-500 hover:text-red-600 transition" title="Удалить">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Пустое состояние -->
                <div v-else class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7h-4.5M20 7v10m0-10l-4-4m4 4l4-4M4 5h4.5M4 5v10m0-10l4 4m-4-4l-4 4" />
                    </svg>
                    <p class="text-gray-500 text-lg">Товары не найдены</p>
                    <p class="text-gray-400 text-sm mt-2">Попробуйте изменить параметры поиска</p>
                </div>

                <!-- Пагинация -->
                <div v-if="paginationLinks.length > 3" class="mt-6 flex justify-center">
                    <div class="flex gap-1">
                        <Link v-for="link in paginationLinks" :key="link.label" :href="link.url || '#'" :class="[
                            'px-3 py-2 rounded-lg text-sm transition',
                            link.active ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'
                        ]" v-html="link.label" />
                    </div>
                </div>

                <!-- СЕКЦИЯ ЭКСПОРТА -->
                <div class="mt-10 pt-10 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 00-2-2V5a2 2 0 002-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Экспорт отчетов
                    </h3>
                    
                    <!-- Блок с ошибками -->
                    <div v-if="exportErrors.length > 0" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-red-800 mb-1">Ошибка при экспорте:</p>
                                <ul class="list-disc list-inside text-red-700 text-sm">
                                    <li v-for="error in exportErrors" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                            <button @click="clearErrors" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Блок 1 - Заказы -->
                        <div class="bg-gray-50 border border-gray-200 p-5 rounded-2xl">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Покупки за период</p>
                            <div class="flex flex-wrap gap-3 items-end">
                                <div class="flex-1 min-w-[140px]">
                                    <span class="text-[10px] uppercase text-gray-400 font-bold ml-1">От</span>
                                    <input 
                                        type="date" 
                                        v-model="startDate" 
                                        :max="endDate || undefined"
                                        class="w-full mt-1 border-gray-300 rounded-xl text-sm focus:ring-black focus:border-black"
                                    >
                                </div>
                                <div class="flex-1 min-w-[140px]">
                                    <span class="text-[10px] uppercase text-gray-400 font-bold ml-1">До</span>
                                    <input 
                                        type="date" 
                                        v-model="endDate" 
                                        :min="startDate || undefined"
                                        class="w-full mt-1 border-gray-300 rounded-xl text-sm focus:ring-black focus:border-black"
                                    >
                                </div>
                                <button 
                                    @click="handleExportOrders" 
                                    :disabled="isLoadingExport"
                                    class="h-[42px] px-6 bg-black text-white rounded-xl hover:bg-gray-800 transition text-sm font-bold shadow-lg shadow-gray-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                >
                                    <svg v-if="isLoadingExport" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ isLoadingExport ? 'Загрузка...' : 'Скачать Excel' }}</span>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-3">* Excel файл с детальным отчетом по заказам</p>
                        </div>

                        <!-- Блок 2 - Остатки -->
                        <div class="bg-gray-50 border border-gray-200 p-5 rounded-2xl">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Наличие и остатки</p>
                                <p class="text-xs text-gray-500 mt-1">Список всех товаров с разделением на активные и закончившиеся</p>
                            </div>
                            <button 
                                @click="handleExportInventory" 
                                :disabled="isLoadingExport"
                                class="mt-4 h-[42px] w-full border-2 border-black text-black rounded-xl hover:bg-black hover:text-white transition text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <svg v-if="isLoadingExport" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ isLoadingExport ? 'Загрузка...' : 'Выгрузить остатки склада' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>

<style scoped>
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>