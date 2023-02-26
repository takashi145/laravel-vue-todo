<script setup>
import axios from 'axios';
import { ref } from 'vue';
import Logo from './Logo.vue';
import { useRouter } from 'vue-router'
import { store } from '../store';

const router = useRouter();

const links = ref([
  {
    name: 'タスク',
    url: '/task'
  },
  {
    name: 'カテゴリ',
    url: '/category'
  },
])

const logout = async() => {
  try {
    await axios.post('/api/logout')
    store.commit('setLoginState', false)
    router.push('/login')
  }catch(e) {
    console.log(e)
  }
}
</script>

<template>
  <header class="h-full w-full flex justify-between items-center px-4 border-b shadow-lg">
    <div class="flex">
      <div>
        <Logo />
      </div>
      <ul class="ml-8 flex">
        <li v-for="(link, index) in links" :key="index" :class="{'underline': this.$route.path == link.url}" class=" mr-3 text-gray-600 hover:text-gray-900">
          <router-link :to="link.url">{{ link.name }}</router-link>
        </li>
      </ul>
    </div>
    
    <div>
      <button @click="logout" class="text-xs bg-gray-400 hover:bg-gray-500 text-white mt-3 p-2 rounded mb-2">ログアウト</button>
    </div>
  </header>
</template>
