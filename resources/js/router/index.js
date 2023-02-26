import { createRouter, createWebHistory } from "vue-router"
import Home from '@/Pages/Home.vue'
import Task from '@/Pages/task/Task.vue'
import Category from '@/Pages/Category.vue'
import Login from '@/Pages/auth/Login.vue'
import Register from '@/Pages/auth/Register.vue'
import { store } from "../store"

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/task',
    name: 'task',
    component: Task,
    meta: {requiresAuth: true}
  },
  {
    path: '/category',
    name: 'category',
    component: Category,
    meta: {requiresAuth: true}
  },
  {
    path: '/category/:id',
    name: 'task_list',
    component: Task,
    meta: {requiresAuth: true},
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {guest: true}
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {guest: true}
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from , next) => {
  if(to.matched.some(record => record.meta.requiresAuth)){
    if(!store.getters.login) {
      next({ path: '/login', query: { redirect: to.fullPath }})
    }else {
      next();
    }
  }else{ 
    if(to.matched.some(record => record.meta.guest)){
      if(!store.getters.login) {
        next();
      }else {
        next('/')
      }
    }else {
      next();
    }
  }
})

export default router
