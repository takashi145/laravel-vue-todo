<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { ref } from 'vue';
import { onMounted } from '@vue/runtime-core';
import axios from 'axios';
import CreateCategory from '../Components/CreateCategory.vue';

const categories = ref([]);

onMounted(() => {
  getCategories()
})

const show = ref(false);

const errors = ref({});

const getCategories =  async () => {
  try {
    await axios.get('/api/category')
      .then((res) => {
        categories.value = res.data
      })
  }catch(e) {
    console.log(e)
  }
}

const deleteCategory = async id => {
  if(!confirm('本当に削除してもよろしいですか')) {
    return;
  }
  await axios.delete(`/api/category/${id}`);
  await getCategories();
}

const createCategory = async form => {
  try {
    const res = await axios.post('/api/category', form);
    categories.value.push(res.data);
    show.value = false;
  }catch(e) {
    errors.value = e.response.data.errors;
  }
}
</script>

<template>
  <auth-layout>
    <div class="text-gray-600 body-font">
      <div class="container px-5 py-8 mx-auto">
        <div class="lg:w-2/3 w-full mx-auto overflow-auto">
          <div class="text-end m-3">
            <button @click="show = true" class="text-blue-400 hover:text-blue-500">+ 追加</button>  
          </div>
          
          <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
              <tr class="">
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">カテゴリー名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タスク数</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in categories" :key="category.id">
                <td class="px-4 py-3"><router-link :to="`/category/${category.id}`">{{ category.name }}</router-link></td>
                <td class="px-4 py-3">{{ category.task_count }}</td>
                <td class="px-4 py-3 text-gray-900 space-x-3">
                  <button @click="deleteCategory" class="text-sm text-blue-400 hover:text-blue-500">編集</button>
                  <button @click="deleteCategory(category.id)" class="text-sm text-red-400 hover:text-red-500">削除</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <CreateCategory :show="show" :errors="errors" @close="show = false" @create="createCategory" />
  </auth-layout>
</template>
