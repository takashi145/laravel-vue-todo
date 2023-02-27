<script setup>
import {ref, defineProps, computed } from 'vue';
import { useRoute } from 'vue-router';

defineProps({
  categories: Array
})

const route = useRoute();

const emit = defineEmits(['select_category']);

const show = ref(false)
</script>

<template>
  <div class="relative">
    <div v-if="show" class="z-40 fixed inset-0 bg-black opacity-50"></div>

    <button @click="show = true" class="text-gray-500 hover:text-gray-600 font-bold text-xl underline">Category →</button>
    <div v-if="show">
      <div class="z-50 w-72 fixed top-0 left-0 h-full shadow-lg bg-gray-100 border-r-2">
        <div class="border-b flex items-center m-3 pb-2">
          <h3 class="text-lg">Categories</h3>
          <button @click="show = false" class="absolute top-3 right-3 text-gray-600 font-bold text-lg hover:scale-125">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>  
        </div>
        
        <div class="mt-3">  
          <ul class="mx-3">
            <li 
              v-for="category in categories" 
              :key="category.id" 
              class="w-full border-b-2 border-gray-300 text-lg m-3 p-1 hover:border-b-2 hover:border-indigo-300 cursor-pointer"
              :class="[route.params.id == category.id ? 'bg-blue-200' : '']"
            >
              <button @click="emit('select_category', category.id); show=false;" class="block w-full">{{ category.name }}</button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
