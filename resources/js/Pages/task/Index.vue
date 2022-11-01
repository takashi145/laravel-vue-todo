<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref, reactive } from 'vue';
import axios from 'axios';

const tasks = ref([]);

const isLoading = ref(false);

const openModal = ref(false);

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
    await axios.get('/api/task')
      .then(res => {
        isLoading.value = true
        tasks.value = res.data
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

const deleteTask = async (id) => {
  try {
    const res = await axios.delete(`/api/task/${id}`)
    getTaskList();
  }catch(e){
    console.log(e)
  }
}
</script>

<template>
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

    <div v-if="!isLoading">
      <div class="fixed inset-0 bg-black opacity-10"></div>
      <div class="flex justify-center">
        <div class="fixed top-80 animate-spin h-16 w-16 border-8 border-t-transparent border-white rounded-full"></div>
      </div>
    </div>

    <section class="text-gray-600 body-font">
      <div class="container px-5 py-12 mx-auto">
        <div class="lg:w-2/3 w-full mx-auto overflow-auto">
          <div class="mb-3 text-right">
            <button @click="openModal = true" class="text-white bg-indigo-400 hover:bg-indigo-500 p-3 rounded">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2 inline-block">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
              </svg>
              Create
            </button>
          </div>
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
                <td class="my-4">
                    <form @submit.prevent="deleteTask(task.id)" class="text-center">
                        <button type="submit" class="w-1/3 mr-12 text-red-400 hover:text-red-500 font-medium">Delete</button>
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
