import { createRouter, createWebHistory } from "vue-router"
import Home from '@/Pages/Home.vue'
import TaskList from '@/Pages/task/TaskList.vue'
import Login from '@/Pages/auth/Login.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/task',
    name: 'task.index',
    component: TaskList
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
