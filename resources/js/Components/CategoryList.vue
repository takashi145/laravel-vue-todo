<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const categories = ref([])

const show = ref(false)

onMounted(() => {
  getCategories();
})

const getCategories = async() => {
  try {
    await axios.get('/api/category')
    .then((res) => {
      categories.value = res.data
      console.log(res)
    })
  }catch(e) {
    console.log(e)
  }
}
</script>

<template>
  <div class="relative">
    <div v-if="show" class="fixed inset-0 bg-black opacity-50"></div>
    <button @click="show = true" class="text-gray-500 hover:text-gray-600 font-bold text-lg underline">Category →</button>
    <div v-if="show">
      <div class="w-60 fixed top-0 left-0 h-full shadow-lg bg-gray-50 border-r-2">
        <button @click="show = false" class="absolute fixed top-3 right-3 text-gray-500 hover:text-gray-600 font-bold text-lg">← Close</button>
        <div class="mt-12 mx-2">
          <h3 class="text-xl border-b-4 border-indigo-300 bg-indigo-200 text-gray-800 p-2 rounded">Category</h3>
          <ul class="mx-3">
            <li v-for="category in categories" :key="category.id" class="text-lg my-3 p-1 hover:bg-indigo-200 cursor-pointer rounded">
              - {{ category.name }}
            </li>
          </ul>  
        </div>
      </div>
    </div>
  </div>
</template>
