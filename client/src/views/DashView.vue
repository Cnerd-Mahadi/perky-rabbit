<script setup lang="ts">
import { baseURL } from '@/utils'
import { onMounted, ref } from 'vue'

const data = ref([])
const categories = ref({
  departments: [],
  achievements: []
})

const filterDepartment = (value: string) => {
  fetch(`${baseURL}/employee/filtered/department?department_id=${value}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${JSON.parse(localStorage.getItem('cred')!!).token}`
    }
  })
    .then((res) => res.json())
    .then((res) => {
      data.value = res.employees
    })
}

const filterAchievement = (value) => {
  fetch(`${baseURL}/employee/filtered/achievement?achievement_id=${value}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${JSON.parse(localStorage.getItem('cred')!!).token}`
    }
  })
    .then((res) => res.json())
    .then((res) => {
      console.log(res)
      data.value = res.employees[0].employees
    })
}

onMounted(() => {
  fetch(`${baseURL}/employee/getAll`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${JSON.parse(localStorage.getItem('cred')!!).token}`
    }
  })
    .then((res) => res.json())
    .then((res) => {
      data.value = res.employees
    })

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

  console.log(data)
})
</script>

<template>
  <section class="py-1 bg-blueGray-50">
    <div class="w-full 2xl:w-8/12 mb-12 xl:mb-0 px-4 mx-auto mt-24">
      <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <div class="flex flex-wrap items-center">
            <div class="relative w-full px-2 max-w-full flex-grow flex-1">
              <h3 class="font-bold text-2xl text-blue-500">Employee List</h3>
            </div>

            <div class="sm:col-span-3 flex-grow flex-1">
              <label for="department" class="block text-sm font-medium leading-6 text-gray-900"
                >Department</label
              >
              <div class="mt-2">
                <select
                  @change="filterDepartment($event.target.value)"
                  id="department"
                  name="department"
                  class="block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 outline-none px-4 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
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
            </div>

            <div class="col-span-8 flex-grow flex-1">
              <label for="achievements" class="block text-sm font-medium leading-6 text-gray-900"
                >Achievements</label
              >
              <div class="mt-2">
                <select
                  @change="filterAchievement($event.target.value)"
                  id="achievement"
                  name="achievement"
                  class="block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 outline-none px-4 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                >
                  <option
                    v-for="achievement in categories.achievements"
                    :key="achievement.id"
                    :value="achievement.id"
                  >
                    {{ achievement.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
              <RouterLink
                to="/emlpoyee/create"
                class="bg-indigo-500 text-white active:bg-indigo-600 text-sm font-bold uppercase px-6 py-2 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                type="button"
              >
                New Employee
              </RouterLink>
            </div>
          </div>
        </div>

        <div class="block w-full overflow-x-auto">
          <table class="items-center bg-transparent w-full border-collapse">
            <thead>
              <tr>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Name
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Email
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Phone
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Address
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Department
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Achievements
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in data" :key="item.id">
                <th
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700"
                >
                  {{ item.name }}
                </th>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  {{ item.email }}
                </td>
                <td
                  class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  {{ item.phone }}
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  {{ item.address }}
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  {{ item.department.name }}
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <p v-for="achievement in item.achievements" :key="achievement.id">
                    {{ achievement.name }} [ {{ achievement.pivot.achievement_date }} ]
                  </p>
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs font-semibold whitespace-nowrap p-4 text-blue-600 hover:text-blue-500"
                >
                  <RouterLink :to="{ name: 'update-employee', params: { employee_id: item.id } }"
                    >Edit</RouterLink
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>
