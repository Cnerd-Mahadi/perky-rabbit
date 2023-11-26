<script setup lang="ts">
import router from '@/router'
import { baseURL } from '@/utils'
import { useVuelidate } from '@vuelidate/core'
import { email, maxLength, minLength, required } from '@vuelidate/validators'
import { computed, onMounted, reactive, ref } from 'vue'

const categories = ref({
  departments: [],
  achievements: []
})

const formData = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  department_id: '',
  achievements: [
    {
      id: '',
      date: ''
    }
  ]
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
    phone: {
      required
    },
    address: {
      required,
      maxLength: maxLength(50)
    },
    department_id: {
      required
    },
    achievements: {
      $each: { id: { required }, date: { required } }
    }
  }
})

const v$ = useVuelidate(rules, formData)

type Achievement = {
  id: string
  date: string
}

const addAchievement = (value: Achievement, index: number) => {
  formData.achievements[index] = {
    id: value.id,
    date: value.date
  }
  console.log(formData.achievements)
  formData.achievements.push({
    id: '',
    date: ''
  })
}

const deleteAchievement = (index: number) => {
  formData.achievements.splice(index, 1)
}

const submitForm = async () => {
  const validation = await v$.value.$validate()
  if (!validation) {
    console.log(v$.value.$errors)
  } else {
    console.log(formData)

    const response = await fetch(`${baseURL}/employee/create`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${JSON.parse(localStorage.getItem('cred')!!).token}`
      },
      body: JSON.stringify(formData)
    })
    if (response.ok) {
      router.push({ name: 'dash' })
    }
  }
}

onMounted(async () => {
  fetch(`${baseURL}/employee/getCategories`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${JSON.parse(localStorage.getItem('cred')!!).token}`
    }
  })
    .then((res) => res.json())
    .then((res) => {
      categories.value.departments = res.departments
      categories.value.achievements = res.achievements
    })
})
</script>

<template>
  <div class="p-4 min-h-screen m-10">
    <form @submit.prevent="submitForm">
      <div class="space-y-12">
        <div class="border-b border-gray-900/10 pb-12">
          <h2 class="text-2xl font-bold leading-7 text-blue-600">Add Employee</h2>

          <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="name" class="block text-sm font-medium leading-6 text-gray-900"
                >Name</label
              >
              <div class="mt-2">
                <input
                  v-model="formData.name"
                  type="text"
                  name="name"
                  id="name"
                  autocomplete="name"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 outline-none px-4 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
              <span
                v-for="error in v$.name.$errors"
                :key="error.$uid"
                class="text-red-500 text-sm font-medium"
                >{{ error.$message }}</span
              >
            </div>

            <div class="sm:col-span-3">
              <label for="email" class="block text-sm font-medium leading-6 text-gray-900"
                >Email Address</label
              >
              <div class="mt-2">
                <input
                  v-model="formData.email"
                  type="email"
                  name="email"
                  id="email"
                  autocomplete="email"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 outline-none px-4 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
              <span
                v-for="error in v$.email.$errors"
                :key="error.$uid"
                class="text-red-500 text-sm font-medium"
                >{{ error.$message }}</span
              >
            </div>

            <div class="sm:col-span-3">
              <label for="phone" class="block text-sm font-medium leading-6 text-gray-900"
                >Phone</label
              >
              <div class="mt-2">
                <input
                  v-model="formData.phone"
                  id="phone"
                  name="phone"
                  type="tel"
                  autocomplete="tel"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 outline-none px-4 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
              <span
                v-for="error in v$.phone.$errors"
                :key="error.$uid"
                class="text-red-500 text-sm font-medium"
                >{{ error.$message }}</span
              >
            </div>

            <div class="sm:col-span-3">
              <label for="department" class="block text-sm font-medium leading-6 text-gray-900"
                >Department</label
              >
              <div class="mt-2">
                <select
                  v-model="formData.department_id"
                  id="department"
                  name="department"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 outline-none px-4 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                >
                  <option
                    v-for="department in categories.departments"
                    :key="department.id"
                    :value="department.id"
                  >
                    {{ department.name }}
                  </option>
                </select>
              </div>
              <span
                v-for="error in v$.department_id.$errors"
                :key="error.$uid"
                class="text-red-500 text-sm font-medium"
                >{{ error.$message }}</span
              >
            </div>

            <div class="col-span-full">
              <label for="address" class="block text-sm font-medium leading-6 text-gray-900"
                >Address</label
              >
              <div class="mt-2">
                <input
                  v-model="formData.address"
                  type="text"
                  name="address"
                  id="address"
                  autocomplete="address"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 outline-none px-4 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
              <span
                v-for="error in v$.address.$errors"
                :key="error.$uid"
                class="text-red-500 text-sm font-medium"
                >{{ error.$message }}</span
              >
            </div>

            <div class="col-span-8">
              <label for="achievements" class="block text-sm font-medium leading-6 text-gray-900"
                >Achievements</label
              >
              <div
                v-for="(achievement, index) in formData.achievements"
                :key="index"
                class="mt-2 flex flex-row gap-4"
              >
                <select
                  v-model="formData.achievements[index].id"
                  id="achievement"
                  name="achievement"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 outline-none px-4 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                >
                  <option
                    v-for="achievement in categories.achievements"
                    :key="achievement.id"
                    :value="achievement.id"
                  >
                    {{ achievement.name }}
                  </option>
                </select>

                <div>
                  <input
                    v-model="formData.achievements[index].date"
                    type="text"
                    placeholder="Achievement Date"
                    onfocus="(this.type='date')"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 outline-none px-4 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                  />
                </div>

                <button
                  v-if="index === formData.achievements.length - 1"
                  class="rounded-md bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                  @click="addAchievement(achievement, index)"
                >
                  Add
                </button>
                <button
                  class="rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                  :class="{
                    'bg-red-600 hover:bg-red-500': formData.achievements.length > 1,
                    'bg-slate-300 hover:bg-slate-300': formData.achievements.length === 1
                  }"
                  :disabled="formData.achievements.length === 1"
                  @click="deleteAchievement(index)"
                >
                  Delete
                </button>
                <!-- <span
                  v-for="error in v$.achievements.$each.$response.$errors[index].name"
                  :key="error.$uid"
                  class="text-red-500 text-sm font-medium"
                  >{{ error.$message }}</span -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-end gap-x-6">
        <RouterLink
          to="/dash"
          class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-500"
        >
          Cancel
        </RouterLink>
        <button
          type="submit"
          class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</template>
