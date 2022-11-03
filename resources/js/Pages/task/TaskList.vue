<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref, reactive } from 'vue';
import axios from 'axios';
import AuthLayout from '../../Layouts/AuthLayout.vue';

const tasks = ref([]);
const taskDetail = ref({});
const isLoading = ref(false);
const openModal = ref(false);
const showTaskModal = ref(false);
const searchForm = reactive({
  deadline: 'all',
  completed: 'all',
  keyword: '',
  deadline_start: null,
  deadline_end: null,
});

const form = reactive({
  title: '',
  description: '',
  deadline: '',
});

onMounted(() => {
  getTaskList();
});

const getTaskList = async () => {
  try {
    isLoading.value = false
    await axios.get('/api/task', { params: searchForm })
      .then(res => {
        setTimeout(() => {
          isLoading.value = true
          tasks.value = res.data
        }, 500);
      });
  }catch(e) {
    console.log(e)
  }
}

const createTask = async () => {
  try {
    await axios.post('/api/task', form)
      .then(res => {
        tasks.value.unshift(res.data)
        form.title = ''
        form.description = ''
        form.deadline = ''
        openModal.value = false
      })
  }catch(e) {
    console.log(e)
  }
}

const showTask = async (id) => {
  try {
    await axios.get(`/api/task/${id}`)
      .then(res => {
        taskDetail.value = res.data
      });
    showTaskModal.value = true;
  }catch(e) {
    console.log(e)
  }
}

const toggleStatus = async (id) => {
  try {
    await axios.put(`/api/task/${id}`)
    isLoading.value = false
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
</script>

<template>
  <auth-layout>
    <div class="m-8">
      <div v-if="openModal" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
          <div class="fixed inset-0 bg-black opacity-50"></div>
          <div class="mx-auto mt-40 relative p-4 w-full md:max-w-3xl h-full md:h-auto">
              <div class="relative bg-gray-100 rounded shadow">
                  <button type="button" @click="openModal = false" class="absolute top-3 right-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                      <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                  </button>
                  <div class="py-6 px-6 lg:px-8">
                      <h3 class="mb-8 text-2xl font-medium text-gray-800">タスク作成</h3>
                      <form @submit.prevent="createTask()" class="space-y-8">
                          <div>
                              <label for="title" class="block mb-2 text-lg font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                              <input type="text" v-model="form.title" name="title" id="title" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="タイトルを入力してください" required>
                          </div>
                          <div>
                              <label for="description" class="block mb-2 text-lg font-medium text-gray-900">Description</label>
                              <textarea v-model="form.description" name="description" id="description" placeholder="詳細を入力してください" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 h-40" required></textarea>
                          </div>
                          <div>
                            <label for="deadline" class="block mb-2 text-lg font-medium text-gray-900">Deadline<span class="text-red-500"> *</span></label>
                            <input type="date" v-model="form.deadline" name="deadline" id="deadline" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="タイトルを入力してください" required>
                          </div>
                          <div class="flex justify-around">
                            <button type="submit" class="w-1/2 mx-3 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Submit</button>
                            <button type="button" @click="openModal = false" class="w-1/2 mx-3 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Close</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      <div v-if="showTaskModal" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
          <div class="fixed inset-0 bg-black opacity-50"></div>
          <div class="mx-auto mt-20 relative p-4 w-full md:max-w-3xl h-full md:h-auto">
              <div class="relative bg-gray-100 rounded shadow">
                  <button type="button" @click="showTaskModal = false" class="absolute top-3 right-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                      <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                  </button>
                  <div class="py-6 px-6 lg:px-8">
                      <h3 class="mb-8 text-2xl font-medium text-gray-800">タスク詳細</h3>
                      <div class="space-y-8">
                          <div>
                              <label for="title" class="block mb-2 text-lg font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                              <div name="title" id="title" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3">{{ taskDetail.title }}</div>
                          </div>
                          <div>
                              <label for="description" class="block mb-2 text-lg font-medium text-gray-900">Description</label>
                              <div name="description" id="description" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 h-40">{{ taskDetail.description }}</div>
                          </div>
                          <div>
                              <label class="block mb-2 text-lg font-medium text-gray-900">Status</label>
                              <div v-if="taskDetail.completed" class="bg-green-400 border border-green-300 text-white text-sm font-bold rounded-lg p-3">
                                完了
                              </div>
                              <div v-else class="bg-red-400 border border-red-300 text-white text-sm font-bold rounded-lg p-3">
                                未完了
                              </div>
                          </div>
                          <div>
                            <label for="deadline" class="block mb-2 text-lg font-medium text-gray-900">Deadline<span class="text-red-500"> *</span></label>
                            <div name="deadline" id="deadline" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3">{{ taskDetail.deadline }}</div>
                          </div>
                          <div class="text-center">
                            <button type="button" @click="showTaskModal = false" class="w-1/2 mx-3 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Close</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div v-if="!isLoading">
        <div class="fixed inset-0 bg-black opacity-10"></div>
        <div class="flex justify-center">
          <div class="fixed top-80 animate-spin h-16 w-16 border-8 border-t-transparent border-white rounded-full"></div>
        </div>
      </div>

      <section class="text-gray-600 body-font">
        <div class="container px-5 py-12 mx-auto">
          <div class="w-full mx-auto overflow-auto">
            <div class="mb-3 text-right">
              <button @click="openModal = true" class="text-white bg-indigo-400 hover:bg-indigo-500 p-3 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2 inline-block">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                Add Task
              </button>
            </div>
            <div class="lg:flex">
              <div class="p-3 mb-3 mr-8 w-full lg:w-1/3">
                <h3 class="text-lg m-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                  絞り込み条件
                </h3>
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
                    <button type="submit" class="text-white bg-indigo-400 hover:bg-indigo-500 p-3 rounded-lg">絞り込む</button>
                  </div>
                </form>
              </div>
              
              <table class="table-auto w-full lg:w-2/3 text-left">
                <thead class="bg-indigo-200">
                  <tr>
                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm rounded-tl rounded-bl">#</th>
                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Title</th>
                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Status</th>
                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm">Deadline</th>
                    <th class="title-font tracking-wider font-medium text-gray-900 text-sm rounded-tr rounded-br"></th>
                  </tr>
                </thead>
                <tbody class="bg-gray-50">
                  <tr v-for="task in tasks" :key="task.id" class="border-b">
                    <td class="px-4 py-3">{{ task.id }}</td>
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
          </div>
        </div>
      </section>
    </div>
  </auth-layout>
</template>
