<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

const categories = ref([])
const show = ref(false)

const form = reactive({
  name: '',
})

const error = ref('');

onMounted(() => {
  getCategories();
})

const getCategories = async() => {
  try {
    await axios.get('/api/category')
    .then((res) => {
      categories.value = res.data
    })
  }catch(e) {
    console.log(e)
  }
}

const createCategory = async() => {
  try {
    await axios.post('/api/category', form)
    .then(() => {
      form.name = ''
      getCategories()
    })
  }catch(e) {
    console.log(e)
    error.value = '同じ名前のカテゴリーは登録できません。'
  }
}
</script>

<template>
  <div class="relative">
    <div v-if="show" class="z-40 fixed inset-0 bg-black opacity-50"></div>

    <button @click="show = true" class="text-gray-500 hover:text-gray-600 font-bold text-xl underline">Category →</button>
    <div v-if="show">
      <div class="z-50 w-72 fixed top-0 left-0 h-full shadow-lg bg-gray-50 border-r-2">
        <button @click="show = false" class="absolute top-3 right-3 text-gray-600 font-bold text-lg hover:scale-125">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <div class="mt-12 mx-2">
          <form @submit.prevent="createCategory()" class="my-2">
            <div class="text-sm text-red-400">{{ error }}</div>
            <input type="text" v-model="form.name" name="category" id="category" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="New Category" required>
            <button type="submit" class="w-full py-2 mt-1 text-white bg-indigo-400 hover:bg-indigo-500 rounded">
              Create
            </button>
          </form>

          <ul class="mx-3">
            <li v-for="category in categories" :key="category.id" class="border-b-2 border-gray-300 text-lg m-3 p-1 hover:border-b-2 hover:border-indigo-300 cursor-pointer">
              <router-link :to="`/category/${category.id}`">{{ category.name }}</router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
