<script setup>
import { ref } from 'vue';
import { reactive } from "@vue/reactivity";
import axios from 'axios';
import { useRouter } from 'vue-router'
import { store } from '../../store';

const router = useRouter();

const form = reactive({
  'email': '',
  'password': '',
});

const error = ref('');

const login = async() => {
  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/api/login', form)
    store.commit('setLoginState', true)
    router.push('/')
  }catch(e) {
    console.log(e);
    error.value = 'ログインに失敗しました。メールアドレスまたはパスワードが違います。'
  }
}

</script>

<template>
  <div class="my-24">
    <div class="p-12 mx-auto border rounded-lg w-full md:w-2/3 lg:w-1/3 shadow-lg">
      <h1 class="text-2xl text-center m-6 font-bold">SimpleTodo</h1>
      <div v-if="error" class="my-3 text-red-500">
        {{ error }}
      </div>
      <form @submit.prevent="login()" class="space-y-8">
          <div>
              <label for="email" class="block mb-2 text-lg font-medium text-gray-900">Email <span class="text-red-500">*</span></label>
              <input type="email" v-model="form.email" name="title" id="title" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="メールアドレスを入力してください" required>
          </div>
          <div>
            <label for="password" class="block mb-2 text-lg font-medium text-gray-900">Password<span class="text-red-500"> *</span></label>
            <input type="password" v-model="form.password" name="password" id="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="パスワードを入力してください" required>
          </div>
          <div class="flex justify-around">
            <button type="submit" class="w-1/2 mx-3 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Login</button>
          </div>
          <div>
            <router-link to="/register" class="text-blue-400 hover:text-blue-500">アカウントをお持ちではありませんか?新規登録</router-link>
          </div>
      </form>
    </div>  
  </div>
</template>
