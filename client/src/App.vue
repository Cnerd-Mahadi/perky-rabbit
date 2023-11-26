<script setup lang="ts">
import NavBar from './components/NavBar.vue'
import { useAuthStore } from './stores/auth'

const auth = useAuthStore()
localStorage.getItem('cred') && auth.setAuth(true)
</script>

<template>
  <NavBar />
  <RouterView />
</template>
