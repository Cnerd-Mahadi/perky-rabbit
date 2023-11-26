<script setup lang="ts">
import SignInForm from '@/components/forms/SignInForm.vue'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
console.log(auth.isLoggedIn)
auth.isLoggedIn && router.push({ name: 'dash' })
</script>

<template>
  <div class="flex items-center justify-center min-h-screen">
    <SignInForm />
  </div>
</template>
