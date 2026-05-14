<template>
    <AuthLayout>
        <RegisterForm />
    </AuthLayout>
</template>

<script setup>
import AppBaseLayout from '../../layouts/AppBaseLayout.vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import RegisterForm from '../../components/forms/auth/RegisterForm.vue'

defineOptions({
    layout: AppBaseLayout
})
</script>
