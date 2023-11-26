import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => {
    return {
      isLoggedIn: false
    }
  },
  actions: {
    setAuth(value: boolean) {
      this.isLoggedIn = value
    }
  }
})
