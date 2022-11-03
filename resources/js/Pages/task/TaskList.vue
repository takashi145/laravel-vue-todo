<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref, reactive } from 'vue';
import axios from 'axios';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import CategoryList from '../../Components/CategoryList.vue';
import CreateForm from '../../Components/CreateForm.vue';
import ShowTask from '../../Components/ShowTask.vue';
const tasks = ref([]);
const taskDetail = ref({});
const isLoading = ref(false);
const openCreateForm = ref(false);
const openTaskDetail = ref(false);
const searchForm = reactive({
  deadline: 'all',
  completed: 'all',
  keyword: '',
  deadline_start: '',
  deadline_end: '',
});
const errors = ref({});

onMounted(() => {
  getTaskList();
});

const closeModal = () => {
  openCreateForm.value = false
  openTaskDetail.value = false
}

const getTaskList = async () => {
  try {
    isLoading.value = true
    await axios.get('/api/task', { params: searchForm })
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

const createTask = async (form) => {
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

const showTask = async (id) => {
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

const toggleStatus = async (id) => {
  try {
    await axios.put(`/api/task/${id}`)
    getTaskList();
  }catch(e) {
    console.log(e)
  }
}

const deleteTask = async (id) => {
  try {
    await axios.delete(`/api/task/${id}`)
    isLoading.value = false
    getTaskList();
  }catch(e){
    console.log(e)
  }
}

const reset = () => {
  searchForm.deadline = 'all'
  searchForm.completed = 'all'
  searchForm.deadline_start = ''
  searchForm.deadline_end = ''
  searchForm.keyword = ''
}
</script>

<template>
  <auth-layout>
    <div class="m-8">
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
              <div class="p-3 mb-3 mr-8 w-full lg:w-1/3">
                <form @submit.prevent="getTaskList()" class="shadow-lg p-8 border rounded">
                  <div class="mb-3">
                    <label for="" class="text-lg">期限で絞り込み</label>
                    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 sm:flex">
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.deadline" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">すべて </label>
                            </div>
                        </li>
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.deadline" value="yet" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">期限前 </label>
                            </div>
                        </li>
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.deadline" value="done" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label class="py-3 ml-2 w-full text-sm font-medium text-gray-900">期限切れ </label>
                            </div>
                        </li>
                    </ul>
                  </div>
                  <div class="mb-3">
                    <label for="" class="text-lg">状態で絞り込み</label>
                    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 sm:flex">
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.completed" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">すべて </label>
                            </div>
                        </li>
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.completed" value="completed" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">完了 </label>
                            </div>
                        </li>
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                            <div class="flex items-center pl-3">
                                <input type="radio" v-model="searchForm.completed" value="incompleted" name="list-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label class="py-3 ml-2 w-full text-sm font-medium text-gray-900">未完了 </label>
                            </div>
                        </li>
                    </ul> 
                  </div>
                  <div class="mb-3">
                    <label for="keyword" class="text-lg">キーワード検索</label>
                    <input type="text" v-model="searchForm.keyword" name="keyword" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="キーワードを入力してください">
                  </div>
                  <div class="mb-5">
                    <label for="deadline" class="text-lg">期限範囲検索</label>
                    <div class="flex">
                      <input type="date" v-model="searchForm.deadline_start" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-3">
                      <div class="text-xl mx-3 mt-2"> ～ </div>
                      <input type="date" v-model="searchForm.deadline_end" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-3">
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="text-white bg-indigo-400 hover:bg-indigo-500 p-3 mr-4 rounded-lg">検索</button>
                    <button type="button" @click="reset()" class="text-white bg-gray-400 hover:bg-gray-500 p-3 rounded-lg">リセット</button>
                  </div>
                </form>
              </div>
              <!---->

              <!--タスク一覧テーブル-->
              <div :class="{'relative': isLoading}" class="w-full lg:w-2/3">
                <div v-if="isLoading">
                  <div class="flex justify-center">
                    <div class="absolute fixed top-60 animate-spin h-16 w-16 border-8 border-t-transparent border-indigo-300 rounded-full"></div>
                  </div>
                </div>
                
                <table :class="{'h-96' : tasks.length == 0}" class="table-auto w-full text-left">
                  <thead class="bg-indigo-200">
                    <tr>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Title</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Status</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Deadline</th>
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
                                Delete
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
