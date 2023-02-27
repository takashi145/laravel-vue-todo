<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref } from 'vue';
import axios from 'axios';
import AuthLayout from './../Layouts/AuthLayout.vue';
import TaskList from '../Components/task/TaskList.vue'
import CategoryList from './../Components/category/CategoryList.vue';
import CreateTask from './../Components/task/CreateTask.vue';
import ShowTask from './../Components/task/ShowTask.vue';
import SearchForm from './../Components/task/SearchForm.vue';
import { useRoute, useRouter } from 'vue-router';

const tasks = ref([]);
const taskDetail = ref({});
const isLoading = ref(false);
const openCreateForm = ref(false);
const openTaskDetail = ref(false);
const openSearchForm = ref(false);
const errors = ref({});

const category = ref(null);

const categories = ref([]);

const route = useRoute();
const router = useRouter();

onMounted(async () => {
  await getTaskList();
  await getCategories();
});

const closeModal = () => {
  openCreateForm.value = false
  openTaskDetail.value = false
  openSearchForm.value = false
}


const getTaskList = async ()  => {
  try {
    const id = route.params.id;
    isLoading.value = true

    let res = null;
    if(id) {
      res =  await axios.get(`/api/category/${route.params.id}/tasks`, {
        params: route.query
      })
      category.value = res.data.category;
    }else {
      res = await axios.get('/api/task', {
        params: route.query
      })
    }
    setTimeout(() => {
      isLoading.value = false
      tasks.value = res.data.tasks
    }, 500);
  }catch(e) {
    isLoading.value = false
  }
}

const createTask = async form => {
  try {
    await axios.post('/api/task', form);
    await getTaskList();
    openCreateForm.value = false;
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
  if(!confirm('本当に削除してもよろしいですか？')) {
    return;
  }
  try {
    await axios.delete(`/api/task/${id}`)
    isLoading.value = false
    getTaskList();
  }catch(e){
    console.log(e)
  }
}

const select_category = async category_id => {
  await router.push(`/category/${category_id}`);
  await getTaskList();
}

const getCategories = async () => {
  const res = await axios.get('/api/category');
  categories.value = res.data
}

</script>

<template>
  <auth-layout>
    <!-- カテゴリ一覧 -->
    <CategoryList :categories="categories" @select_category="select_category" />

    <!-- タスク詳細 -->
    <ShowTask :open="openTaskDetail" :task="taskDetail" @closeModal="closeModal" />

    <!-- タスク作成フォーム -->
    <CreateTask :open="openCreateForm" :categories="categories" :errors="errors" @closeModal="closeModal" @createTask="createTask" />
    
    <div v-if="category" class="mt-4 flex items-center">
      <label for="" class="mr-3">カテゴリ: </label>
      <h3 class="text-lg underline">{{ category.name }}</h3>
    </div>

    <div v-else class="mt-4 flex items-center">
      <h3 class="text-lg underline">すべてのタスク</h3>
    </div>

    <div class="lg:flex my-8 w-full mx-auto overflow-auto">
      <!--検索フォーム-->
      <button @click="openSearchForm = !openSearchForm" :class="{'block': openSearchForm}" class="lg:hidden text-white bg-indigo-300 hover:bg-indigo-400 p-2 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        絞り込み
      </button>
      <SearchForm :open="openSearchForm" @search="getTaskList" />

      <!--タスク一覧-->
      <TaskList :tasks="tasks" :isLoading="isLoading" @showTask="showTask" @toggleStatus="toggleStatus" @deleteTask="deleteTask" />
    </div>

    <button @click="openCreateForm = true" class="fixed bottom-10 right-5 text-white bg-indigo-400 hover:bg-indigo-500 p-4 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
      </svg>
    </button>

  </auth-layout>
</template>
