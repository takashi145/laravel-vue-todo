<script setup>
import { onMounted } from '@vue/runtime-core';
import { ref } from 'vue';
import axios from 'axios';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import TaskList from '../../Components/TaskList.vue';
import CategoryList from '../../Components/CategoryList.vue';
import CreateTask from '../../Components/CreateTask.vue';
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
    <!--カテゴリー-->
    <CategoryList />

    <!-- タスク詳細 -->
    <ShowTask :open="openTaskDetail" :task="taskDetail" @closeModal="closeModal" />

    <!-- タスク作成フォーム -->
    <CreateTask :open="openCreateForm" :errors="errors" @closeModal="closeModal" @createTask="createTask" />

    <SearchForm @search="getTaskList" />

    <TaskList :tasks="tasks" :isLoading="isLoading" @toggleStatus="toggleStatus" @deleteTask="deleteTask" />

    <button @click="openCreateForm = true" class="fixed bottom-10 right-5 text-white bg-indigo-400 hover:bg-indigo-500 p-4 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
      </svg>
    </button>
  </auth-layout>
</template>
