<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref } from 'vue';
import axios from 'axios';

const tasks = ref([]);

const isLoading = ref(false);

onMounted(() => {
  getTaskList();
});

const getTaskList = async () => {
  try {
    await axios.get('/api/task')
      .then(res => {
        isLoading.value = true
        tasks.value = res.data
        
      });
  }catch(e) {
    console.log(e)
  }
}

</script>

<template>
  <div class="m-8">
    
    <div v-if="!isLoading">
      <div class="fixed inset-0 bg-black opacity-10"></div>
      <div class="flex justify-center">
        <div class="fixed top-80 animate-spin h-16 w-16 border-8 border-t-transparent border-white rounded-full"></div>
      </div>
    </div>

    <section class="text-gray-600 body-font">
      <div class="container px-5 py-24 mx-auto">
        <div class="lg:w-2/3 w-full mx-auto overflow-auto">
          <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
              <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">#</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Title</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Deadline</th>
                <th class="title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">Edit</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="task in tasks" :key="task.id">
                <td class="px-4 py-3">{{ task.id }}</td>
                <td class="px-4 py-3">{{ task.title }}</td>
                <td class="px-4 py-3">
                  <span v-if="task.completed" class="text-sm p-2 rounded text-white bg-green-400">完了</span>
                  <span v-else class="text-sm p-2 rounded text-white bg-red-400">未完了</span>
                </td>
                <td class="px-4 py-3 text-lg text-gray-900">{{ task.deadline }}</td>
                <td class="flex my-4">
                  <router-link to="/" class="text-blue-400 hover:text-blue-500 mr-3">編集</router-link>
                  <form>
                    <button class="text-red-400 hover:text-red-500">削除</button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
  
</template>
