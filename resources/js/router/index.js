import { createRouter, createWebHistory } from "vue-router"
import TaskList from '@/Pages/TaskList.vue'
import Category from '@/Pages/CategoryList.vue'
import Login from '@/Pages/auth/Login.vue'
import Register from '@/Pages/auth/Register.vue'
import { store } from "../store"

const routes = [
  {
    path: '/task',
    name: 'tasks',
    component: TaskList,
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
    component: TaskList,
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
        next({ path: '/task' })
      }
    }else {
      next();
    }
  }
})

export default router
