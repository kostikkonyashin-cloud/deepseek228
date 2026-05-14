<template>
    <AuthLayout>
        <LoginForm :social-providers="social_providers" />
    </AuthLayout>
</template>

<script setup>
defineOptions({
    layout: AppBaseLayout
})

defineProps(['social_providers'])
import AppBaseLayout from '../../layouts/AppBaseLayout.vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import LoginForm from '../../components/forms/auth/LoginForm.vue'

</script>
