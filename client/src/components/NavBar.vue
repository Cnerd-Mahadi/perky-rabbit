<script setup lang="ts">
import router from '@/router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const showLogOut = auth.isLoggedIn

const logOut = () => {
  auth.setAuth(false)
  localStorage.removeItem('cred')
  router.push({ name: 'signin' })
}
</script>

<template>
  <nav class="flex items-center justify-between px-6 py-4 bg-cyan-950">
    <h3 class="text-2xl font-bold text-zinc-300">Perky Rabbit.</h3>
    <button @click="logOut" v-if="showLogOut" class="text-neutral-50 p-4 font-medium rounded-md">
      logOut
    </button>
  </nav>
</template>
