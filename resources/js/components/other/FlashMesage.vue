<template>
    <div class="fixed top-4 right-4 z-50 space-y-3 max-w-md">
        <TransitionGroup enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full scale-95" enter-to-class="opacity-100 translate-x-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0 scale-100" leave-to-class="opacity-0 translate-x-full scale-95">
            <div v-for="message in messages" :key="message.id"
                class="group flex items-start gap-3 p-4 rounded-xl shadow-lg backdrop-blur-sm transition-all duration-300 hover:scale-[1.02] hover:shadow-xl"
                :class="{
                    'bg-gradient-to-r from-green-500 to-green-600': message.type === 'success',
                    'bg-gradient-to-r from-red-500 to-red-600': message.type === 'error',
                    'bg-gradient-to-r from-yellow-500 to-orange-500': message.type === 'warning',
                    'bg-gradient-to-r from-blue-500 to-blue-600': message.type === 'info',
                }">
                <!-- Иконка -->
                <div class="flex-shrink-0">
                    <svg v-if="message.type === 'success'" class="w-5 h-5 text-white" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg v-else-if="message.type === 'error'" class="w-5 h-5 text-white" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg v-else-if="message.type === 'warning'" class="w-5 h-5 text-white" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Текст -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ message.text }}</p>
                </div>

                <button @click="removeMessage(message.id)"
                    class="shrink-0 text-white/70 hover:text-white transition-all duration-200 hover:scale-110 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useFlashMessages } from '@/composables/useFlashMessages'

const { messages, removeMessage } = useFlashMessages()
</script>
