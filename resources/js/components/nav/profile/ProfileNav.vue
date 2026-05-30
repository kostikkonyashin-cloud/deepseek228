<template>
    <nav class="space-y-1">
        <Link v-for="item in navItems" :key="item.key" :href="item.href" :class="[
            'block w-full text-left px-4 py-2 rounded-lg transition',
            isActive(item.href)
                ? 'bg-gray-900 text-white'
                : 'text-gray-700 hover:bg-gray-100'
        ]">
            {{ item.label }}
        </Link>

        <template v-if="hasOrderAccess">
            <div class="border-t border-gray-200 my-2 pt-2">
                <p class="text-xs text-gray-400 px-4 py-1">Управление заказами</p>
            </div>
            <Link :href="route('profile.management.index')" :class="[
                'block w-full text-left px-4 py-2 rounded-lg transition',
                isActive(route('profile.management.index'))
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 hover:bg-gray-100'
            ]">
                Заказы
            </Link>
        </template>

        <template v-if="hasAdminAccess">
            <div class="border-t border-gray-200 my-2 pt-2">
                <p class="text-xs text-gray-400 px-4 py-1">Управление</p>
            </div>
            <Link v-for="item in adminNavItems" :key="item.key" :href="item.href" :class="[
                'block w-full text-left px-4 py-2 rounded-lg transition',
                isActive(item.href)
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 hover:bg-gray-100'
            ]">
                {{ item.label }}
            </Link>
        </template>

        <template v-if="hasAdminAccess">
            <div class="border-t border-gray-200 my-2 pt-2">
                <p class="text-xs text-gray-400 px-4 py-1">Справочники</p>
            </div>
            <Link v-for="item in catalogNavItems" :key="item.key" :href="item.href" :class="[
                'block w-full text-left px-4 py-2 rounded-lg transition',
                isActive(item.href)
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 hover:bg-gray-100'
            ]">
                {{ item.label }}
            </Link>
        </template>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const navItems = [
    { key: 'orders', label: 'Мои заказы', href: route('profile.index') },
    { key: 'favorites', label: 'Избранное', href: route('favorites.index') },
]

const adminNavItems = [
    { key: 'admin-users', label: 'Пользователи', href: route('admin.user.index') },
    { key: 'admin-products', label: 'Товары', href: route('admin.product.index') },
    { key: 'admin-members', label: 'Команда', href: route('admin.team.index') },
    { key: 'admin-workshop-services', label: 'Услуги мастерской', href: route('admin.workshop.index') },
]

const catalogNavItems = [
    { key: 'admin-categories', label: 'Категории', href: route('admin.category.index') },
    { key: 'admin-colors', label: 'Цвета', href: route('admin.color.index') },
    { key: 'admin-sizes', label: 'Размеры', href: route('admin.size.index') },
    { key: 'admin-materials', label: 'Материалы', href: route('admin.material.index') },
    { key: 'admin-brands', label: 'Бренды', href: route('admin.brand.index') },
]

const hasOrderAccess = computed(() => {
    const role = page.props.user_role
    return role === 'Администратор' || role === 'Менеджер по продажам'
})

const hasAdminAccess = computed(() => {
    return page.props.user_role === 'Администратор'
})

const isActive = (href) => {
    return page.url === href
}
</script>
