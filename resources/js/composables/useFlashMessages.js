import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const messages = ref([])

export function useFlashMessages() {
    const page = usePage()

    const addMessage = (message, type = 'info') => {
        const id = Date.now() + Math.random()
        messages.value.push({ id, text: message, type })

        setTimeout(() => {
            messages.value = messages.value.filter(m => m.id !== id)
        }, 5000)
    }

    const removeMessage = (id) => {
        messages.value = messages.value.filter(m => m.id !== id)
    }

    watch(
        () => page.props.flash,
        (newFlash) => {
            if (newFlash?.success) {
                addMessage(newFlash.success, 'success')
            }
            if (newFlash?.error) {
                addMessage(newFlash.error, 'error')
            }
            if (newFlash?.warning) {
                addMessage(newFlash.warning, 'warning')
            }
            if (newFlash?.info) {
                addMessage(newFlash.info, 'info')
            }
        },
        { deep: true, immediate: true }
    )

    return {
        messages,
        removeMessage
    }
}
