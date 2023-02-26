<script setup>
import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';

defineProps({
  open: Boolean
})

const route = useRoute();
const router = useRouter();

const emit = defineEmits(['search'])

const form = reactive
({
  deadline: 'all',
  completed: 'all',
  keyword: '',
  deadline_start: '',
  deadline_end: '',
});

const search = async () => {
  await router.push({
    path: `/category/${route.params.id}`,
    query: form
  });
  emit('search')
}

const reset = async () => {
  form.deadline = 'all'
  form.completed = 'all'
  form.deadline_start = ''
  form.deadline_end = ''
  form.keyword = ''
  await search();
}

</script>

<template>
  <div :class="{'hidden': !open}" class="lg:block p-3 mb-3 mr-8 w-full lg:w-1/3">
    <form @submit.prevent="search()" class="shadow-lg p-8 border rounded">
      <div class="mb-3">
        <label for="" class="text-lg">期限で絞り込み</label>
        <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 sm:flex">
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                <div class="flex items-center pl-3">
                    <input type="radio" v-model="form.deadline" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">すべて </label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                <div class="flex items-center pl-3">
                    <input type="radio" v-model="form.deadline" value="yet" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">期限前 </label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                <div class="flex items-center pl-3">
                    <input type="radio" v-model="form.deadline" value="done" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
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
                    <input type="radio" v-model="form.completed" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">すべて </label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                <div class="flex items-center pl-3">
                    <input type="radio" v-model="form.completed" value="completed" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label  class="py-3 ml-2 w-full text-sm font-medium text-gray-900">完了 </label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                <div class="flex items-center pl-3">
                    <input type="radio" v-model="form.completed" value="incompleted" name="list-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <label class="py-3 ml-2 w-full text-sm font-medium text-gray-900">未完了 </label>
                </div>
            </li>
        </ul> 
      </div>
      <div class="mb-3">
        <label for="keyword" class="text-lg">キーワード検索</label>
        <input type="text" v-model="form.keyword" name="keyword" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="キーワードを入力してください">
      </div>
      <div class="mb-5">
        <label for="deadline" class="text-lg">期限範囲検索</label>
        <div class="flex">
          <input type="date" v-model="form.deadline_start" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-3">
          <div class="text-xl mx-3 mt-2"> ～ </div>
          <input type="date" v-model="form.deadline_end" id="keyword" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-3">
        </div>
      </div>
      <div>
        <button type="submit" class="text-white bg-indigo-400 hover:bg-indigo-500 p-3 mr-4 rounded-lg">検索</button>
        <button type="button" @click="reset()" class="text-white bg-gray-400 hover:bg-gray-500 p-3 rounded-lg">リセット</button>
      </div>
    </form>
  </div>
</template>

