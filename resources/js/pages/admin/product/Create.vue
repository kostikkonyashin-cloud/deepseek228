<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import ProfileLayout from '../../../layouts/ProfileLayout.vue'

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    brands: {
        type: Array,
        default: () => []
    },
    categoryTypes: {
        type: Array,
        default: () => []
    },
    colors: {
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
    }
})

const form = useForm({
    name: '',
    description: '',
    price: '',
    discount: null,
    product_count: 0,
    is_available: true,
    category_id: '',
    brand_id: '',
    category_type_id: null,
    colors: [],
    sizes: [],
    materials: [],
    images: []
})

const imagePreviews = ref([])

const filteredCategoryTypes = computed(() => {
    if (!form.category_id) return []
    return props.categoryTypes.filter(type => type.category_id === form.category_id)
})

const submit = () => {
    form.post(route('admin.product.store'), {
        onSuccess: () => { }
    })
}

const onCategoryChange = () => {
    form.category_type_id = null
}

const onImageChange = (event) => {
    const files = Array.from(event.target.files)
    form.images = [...form.images, ...files]

    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreviews.value.push(e.target.result)
        }
        reader.readAsDataURL(file)
    })
}

const removeImage = (index) => {
    form.images.splice(index, 1)
    imagePreviews.value.splice(index, 1)
}
</script>

<template>
    <ProfileLayout>
        <template #content>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Создание товара</h3>
                    <Link :href="route('admin.product.index')"
                        class="text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Назад к списку
                    </Link>
                </div>

                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Изображения -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Изображения товара</label>
                        <div class="flex flex-wrap gap-4 mb-3">
                            <div v-for="(preview, index) in imagePreviews" :key="index" class="relative w-24 h-24">
                                <img :src="preview" :alt="`Preview ${index + 1}`"
                                    class="w-full h-full object-cover rounded-lg border">
                                <button type="button" @click="removeImage(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                            <label
                                class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-purple-500 transition">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-xs text-gray-400">Добавить</span>
                                <input type="file" @change="onImageChange" multiple
                                    accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                            </label>
                        </div>
                        <p class="text-xs text-gray-400">Максимум 10 изображений. Допустимые форматы: JPEG, PNG, JPG,
                            WEBP. Максимум 2MB</p>
                        <p v-if="form.errors.images" class="mt-1 text-sm text-red-500">{{ form.errors.images }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-800 pb-2 border-b">Основная информация</h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Название <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                    :class="{ 'border-red-500': form.errors.name }">
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Категория <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.category_id" @change="onCategoryChange" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                    :class="{ 'border-red-500': form.errors.category_id }">
                                    <option value="">Выберите категорию</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-500">{{
                                    form.errors.category_id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Бренд <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.brand_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                    :class="{ 'border-red-500': form.errors.brand_id }">
                                    <option value="">Выберите бренд</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                        {{ brand.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-500">{{ form.errors.brand_id
                                    }}</p>
                            </div>

                            <div v-if="filteredCategoryTypes.length > 0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Тип категории</label>
                                <select v-model="form.category_type_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Не выбрано</option>
                                    <option v-for="type in filteredCategoryTypes" :key="type.id" :value="type.id">
                                        {{ type.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-800 pb-2 border-b">Цены и наличие</h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Цена <span class="text-red-500">*</span>
                                </label>
                                <input type="number" v-model="form.price" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                    :class="{ 'border-red-500': form.errors.price }">
                                <p v-if="form.errors.price" class="mt-1 text-sm text-red-500">{{ form.errors.price }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Скидка (%)</label>
                                <input type="number" v-model="form.discount" min="0" max="100"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Количество на складе <span class="text-red-500">*</span>
                                </label>
                                <input type="number" v-model="form.product_count" required min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                    :class="{ 'border-red-500': form.errors.product_count }">
                                <p v-if="form.errors.product_count" class="mt-1 text-sm text-red-500">{{
                                    form.errors.product_count }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" v-model="form.is_available" id="is_available"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                <label for="is_available" class="text-sm text-gray-700 cursor-pointer">
                                    Товар доступен для продажи
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-800 pb-2 border-b">Характеристики</h4>

                        <div v-if="colors.length">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Цвета</label>
                            <div class="flex flex-wrap gap-3">
                                <label v-for="color in colors" :key="color.id"
                                    class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :value="color.id" v-model="form.colors"
                                        class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                    <span class="w-4 h-4 rounded-full border border-gray-300"
                                        :style="{ backgroundColor: color.code }"></span>
                                    <span class="text-sm text-gray-700">{{ color.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div v-if="sizes.length">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Размеры</label>
                            <div class="flex flex-wrap gap-3">
                                <label v-for="size in sizes" :key="size.id"
                                    class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :value="size.id" v-model="form.sizes"
                                        class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                    <span class="text-sm text-gray-700">{{ size.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div v-if="materials.length">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Материалы</label>
                            <div class="flex flex-wrap gap-3">
                                <label v-for="material in materials" :key="material.id"
                                    class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" :value="material.id" v-model="form.materials"
                                        class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                    <span class="text-sm text-gray-700">{{ material.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Описание <span class="text-red-500">*</span>
                        </label>
                        <textarea v-model="form.description" rows="6" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                            :class="{ 'border-red-500': form.errors.description }"></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">{{ form.errors.description
                            }}</p>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Создать товар' }}
                        </button>
                        <Link :href="route('admin.product.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                            Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </template>
    </ProfileLayout>
</template>
