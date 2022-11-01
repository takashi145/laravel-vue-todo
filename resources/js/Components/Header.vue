<script setup>
import { ref } from 'vue';
import Logo from './Logo.vue';

const links = ref([
  {
    name: 'Home',
    url: '/'
  },
  {
    name: 'TaskList',
    url: '/task'
  },
])

const logout = () => {
  alert("logout")
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
      <button @click="logout" class="bg-gray-400 hover:bg-gray-500 text-white mt-3 p-2 rounded mb-2">Logout</button>
    </div>
  </header>
</template>
