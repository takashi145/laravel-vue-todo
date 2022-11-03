<script setup>
import axios from 'axios';
import { ref } from 'vue';
import Logo from './Logo.vue';
import { useRouter } from 'vue-router'
import { store } from '../store';

const router = useRouter();

const links = ref([
  {
    name: 'Todoリスト',
    url: '/task'
  },
  {
    name: 'カテゴリー管理',
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
  <header class="flex justify-between px-4 border-b h-16 shadow-lg">
    <div class="flex pt-5">
      <div>
        <Logo />
      </div>
      <ul class="ml-8 flex">
        <li v-for="(link, index) in links" :key="index" :class="{'underline': this.$route.path == link.url}" class="mr-4 text-gray-600 hover:text-gray-900">
          <router-link :to="link.url">{{ link.name }}</router-link>
        </li>
      </ul>
    </div>
    
    <div>
      <button @click="logout" class="bg-gray-400 hover:bg-gray-500 text-white mt-3 p-2 rounded mb-2">ログアウト</button>
    </div>
  </header>
</template>
