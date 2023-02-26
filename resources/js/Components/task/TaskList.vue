<script setup>
import { ref } from 'vue';
import SearchForm from './SearchForm.vue';

defineProps({
  tasks: Array,
  isLoading: Boolean
});

const emit = defineEmits(['showTask', 'toggleStatus', 'deleteTask']);

const showTask = id => {
  emit('showTask', id)
}

const toggleStatus = id => {
  emit('toggleStatus', id);
}

const deleteTask = id => {
  emit('deleteTask', id);
}

</script>

<template>
  <div :class="{'relative': isLoading}" class="w-full mb-12 h-full">
    <div v-if="isLoading" class="flex justify-center">
      <div class=" fixed inset-0 bg-gray-500 opacity-10"></div>
      <div class=" fixed top-60 animate-spin h-16 w-16 border-8 border-t-transparent border-indigo-300 rounded-full"></div>
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
      <tbody v-if="tasks.length >= 1" class="bg-gray-50">
        <tr v-for="task in tasks" :key="task.id" class="border-b">
          <td class="px-4 py-3">
            <button @click="showTask(task.id)">{{ task.title }}</button>
          </td>
          <td class="px-4 py-3">
            <form @submit.prevent="toggleStatus(task.id)">
              <button type="submit" class="">
                <div v-if="task.completed" class="text-sm p-2 rounded text-white bg-green-400 hover:bg-green-500">完了</div>
                <div v-else class="text-sm p-2 rounded text-white bg-red-400 hover:bg-red-500">未完了</div>  
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
      <tbody v-else>
        <div class="fixed m-8 text-lg">タスクがありません。</div>
      </tbody>
    </table> 
  </div>
</template>
