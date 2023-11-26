import { createRouter, createWebHistory } from 'vue-router'
import DashView from '../views/DashView.vue'
import SignInView from '../views/SignInView.vue'
import SignUpView from '../views/SignUpView.vue'
import AddEmployeeView from '../views/employee/AddEmployee.vue'
import UpdateEmployeeView from '../views/employee/UpdateEmployee.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      name: 'home',
      path: '/',
      redirect: '/signin'
    },
    {
      path: '/signin',
      name: 'signin',
      component: SignInView
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignUpView
    },
    {
      path: '/dash',
      name: 'dash',
      component: DashView
    },
    { path: '/emlpoyee/create', name: 'create-employee', component: AddEmployeeView },
    {
      path: '/emlpoyee/update/:employee_id',
      name: 'update-employee',
      component: UpdateEmployeeView
    }
  ]
})

export default router
