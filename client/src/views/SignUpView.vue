<script setup lang="ts">
import SignUpForm from '@/components/forms/SignUpForm.vue'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
auth.isLoggedIn && router.push({ name: 'dash' })
</script>

<template>
  <div class="flex items-center justify-center min-h-screen">
    <SignUpForm />
  </div>
</template>
