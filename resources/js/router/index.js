import { createRouter, createWebHistory } from "vue-router"
import Home from '@/Pages/Home.vue'
import TaskList from '@/Pages/task/Index.vue'

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
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
