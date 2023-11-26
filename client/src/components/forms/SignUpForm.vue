<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { baseURL } from '@/utils'
import { useVuelidate } from '@vuelidate/core'
import { email, minLength, required, sameAs } from '@vuelidate/validators'
import { computed, reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'

const auth = useAuthStore()
const uniqueEmailError = ref([])
const formData = reactive({
  name: '',
  email: '',
  password: '',
  confirm_password: ''
})

const rules = computed(() => {
  return {
    name: {
      required,
      minLength: minLength(3)
    },
    email: {
      required,
      email
    },
    password: {
      required,
      minLength: minLength(5)
    },
    confirm_password: {
      required,
      sameAs: sameAs(formData.password)
    }
  }
})

const v$ = useVuelidate(rules, formData)

const submitForm = async () => {
  const validation = await v$.value.$validate()
  if (!validation) {
    console.log(v$.value.$errors)
  } else {
    console.log(formData)

    const response = await fetch(`${baseURL}/signup`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    if (response.ok) {
      const data = await response.json()
      localStorage.setItem('cred', JSON.stringify({ token: data.token, user: data.user }))
      auth.setAuth(true)
      window.location.reload()
    } else {
      const data = await response.json()
      if (data.error.email) {
        uniqueEmailError.value = data.error.email
      }
    }
  }
}
</script>

<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
        Sign Up
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" @submit.prevent="submitForm">
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
          <div class="mt-2">
            <input
              v-model="formData.name"
              id="name"
              name="name"
              type="text"
              autocomplete="name"
              class="block w-full rounded-md border-0 py-1.5 px-2 outline-none text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
          <span
            v-for="error in v$.name.$errors"
            :key="error.$uid"
            class="text-red-500 text-sm font-medium"
            >{{ error.$message }}</span
          >
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900"
            >Email address</label
          >
          <div class="mt-2">
            <input
              v-model="formData.email"
              id="email"
              name="email"
              autocomplete="email"
              class="block w-full rounded-md border-0 py-1.5 px-2 outline-none text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
          <span
            v-for="error in v$.email.$errors"
            :key="error.$uid"
            class="text-red-500 text-sm font-medium"
            >{{ error.$message }}</span
          >
          <span
            v-for="(error, index) in uniqueEmailError"
            :key="index"
            class="text-red-500 text-sm font-medium"
            >{{ error }}</span
          >
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900"
              >Password</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="formData.password"
              id="password"
              name="password"
              type="password"
              class="block w-full rounded-md border-0 py-1.5 px-2 outline-none text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
          <span
            v-for="error in v$.password.$errors"
            :key="error.$uid"
            class="text-red-500 text-sm font-medium"
            >{{ error.$message }}</span
          >
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="confirm_password" class="block text-sm font-medium leading-6 text-gray-900"
              >Confirm Password</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="formData.confirm_password"
              id="confirm_password"
              name="confirm_password"
              type="password"
              class="block w-full rounded-md border-0 py-1.5 px-2 outline-none text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
          </div>
          <span
            v-for="error in v$.confirm_password.$errors"
            :key="error.$uid"
            class="text-red-500 text-sm font-medium"
            >{{ error.$message }}</span
          >
        </div>

        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Sign Up
          </button>
        </div>
      </form>
    </div>
    <p class="mt-10 text-center text-sm text-gray-500">
      Already have an account?
      {{ ' ' }}
      <RouterLink to="/signin" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
        >Sign In</RouterLink
      >
    </p>
  </div>
</template>
