<script setup>
import { Link } from '@inertiajs/vue3'
import AppBaseLayout from './AppBaseLayout.vue'
import ProfileUserInfoCard from '../components/cards/profile/ProfileUserInfoCard.vue'
import ProfileNav from '../components/nav/profile/ProfileNav.vue';
</script>

<template>
    <AppBaseLayout>
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <ProfileUserInfoCard />

            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-sm p-4 sticky top-20">
                        <ProfileNav />
                    </div>
                </aside>

                <div class="lg:w-3/4">
                    <slot name="content" />
                </div>
            </div>
        </div>
        <slot name="modal" />
    </AppBaseLayout>
</template>
