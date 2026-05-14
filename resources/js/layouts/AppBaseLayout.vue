<template>
    <div class="min-h-screen flex flex-col">
        <Header />
        <main class="flex-1 max-w-7xl mx-auto px-4 w-full">
            <slot />
        </main>
        <Footer />

        <FlashMesage />
    </div>
</template>

<script setup>
import Header from '../components/partials/Header.vue'
import Footer from '../components/partials/Footer.vue'
import FlashMesage from '../components/other/FlashMesage.vue';
</script>
