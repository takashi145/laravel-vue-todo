<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref } from 'vue';
import axios from 'axios';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import CategoryList from '../../Components/CategoryList.vue';
import CreateForm from '../../Components/CreateForm.vue';
import ShowTask from '../../Components/ShowTask.vue';
import SearchForm from '../../Components/SearchForm.vue';

const tasks = ref([]);
const taskDetail = ref({});
const isLoading = ref(false);
const openCreateForm = ref(false);
const openTaskDetail = ref(false);
const errors = ref({});

onMounted(() => {
  getTaskList();
});

const closeModal = () => {
  openCreateForm.value = false
  openTaskDetail.value = false
}

const getTaskList = async form => {
  try {
    isLoading.value = true
    await axios.get('/api/task', { params: form })
      .then(res => {
        setTimeout(() => {
          isLoading.value = false
          tasks.value = res.data
        }, 500);
      });
  }catch(e) {
    isLoading.value = false
    console.log(e)
  }
}

const createTask = async form => {
  try {
    await axios.post('/api/task', form)
      .then(res => {
        tasks.value.unshift(res.data)
        openCreateForm.value = false
      })
  }catch(e) {
    errors.value = e.response.data.errors
  }
}

const showTask = async id => {
  try {
    await axios.get(`/api/task/${id}`)
      .then(res => {
        taskDetail.value = res.data
        openTaskDetail.value = true
      });
  }catch(e) {
    console.log(e)
  }
}

const toggleStatus = async id => {
  try {
    await axios.put(`/api/task/${id}`)
    getTaskList();
  }catch(e) {
    console.log(e)
  }
}

const deleteTask = async id => {
  try {
    await axios.delete(`/api/task/${id}`)
    isLoading.value = false
    getTaskList();
  }catch(e){
    console.log(e)
  }
}

</script>

<template>
  <auth-layout>
    <div class="m-8">
      <!--カテゴリー一覧(サイドバー)-->
      <CategoryList />

      <!-- 詳細モーダル -->
      <ShowTask :open="openTaskDetail" :task="taskDetail" @closeModal="closeModal" />

      <!-- タスク作成フォームモーダル -->
      <CreateForm :open="openCreateForm" :errors="errors" @closeModal="closeModal" @createTask="createTask" />

      <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">
          <div class="w-full mx-auto overflow-auto">
            <div class="lg:flex lg:mt-8">
              <!--検索フォーム-->
              <SearchForm @search="getTaskList" />

              <!--タスク一覧テーブル-->
              <div :class="{'relative': isLoading}" class="w-full lg:w-2/3">
                <div v-if="isLoading" class="flex justify-center">
                  <div class="absolute fixed inset-0 bg-gray-500 opacity-10"></div>
                  <div class="absolute fixed top-60 animate-spin h-16 w-16 border-8 border-t-transparent border-indigo-300 rounded-full"></div>
                </div>
                
                <table :class="{'h-96' : tasks.length == 0}" class="table-auto w-full text-left">
                  <thead class="bg-indigo-200">
                    <tr>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">タイトル</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">状態</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">期限</th>
                      <th class="title-font tracking-wider font-medium text-gray-900 text-sm rounded-tr rounded-br"></th>
                    </tr>
                  </thead>
                  <tbody class="bg-gray-50">
                    <tr v-for="task in tasks" :key="task.id" class="border-b">
                      <td class="px-4 py-3">
                        <button @click="showTask(task.id)">{{ task.title }}</button>
                      </td>
                      <td class="px-4 py-3">
                        <form @submit.prevent="toggleStatus(task.id)">
                          <button type="submit">
                            <span v-if="task.completed" class="text-sm p-2 rounded text-white bg-green-400 hover:bg-green-500">完了</span>
                            <span v-else class="text-sm p-2 rounded text-white bg-red-400 hover:bg-red-500">未完了</span>  
                          </button>
                        </form>
                      </td>
                      <td class="px-4 py-3 text-lg text-gray-900">{{ task.deadline }}</td>
                      <td class="my-4">
                          <form @submit.prevent="deleteTask(task.id)" class="text-center">
                              <button type="submit" class="mr-12 text-red-400 hover:text-red-500 font-medium hover:bg-red-50 p-2 rounded ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                削除
                              </button>
                          </form>
                      </td>
                    </tr>
                  </tbody>
                </table> 
              </div> 
              <!---->
            </div>
          </div>
        </div>
      </section>
    </div>
    <button @click="openCreateForm = true" class="fixed bottom-10 right-5 text-white bg-indigo-400 hover:bg-indigo-500 p-4 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
      </svg>
    </button>
  </auth-layout>
</template>
