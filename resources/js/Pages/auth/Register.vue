<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'
import GuestLayout from '../../Layouts/GuestLayout.vue';

const router = useRouter();

const form = reactive({
  'name': '',
  'email': '',
  'password': '',
  'password_confirmation': '',
});

const errors = ref({});

const register = async() => {
  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/api/register', form)
    await axios.post('/api/login', {
      email : form.email,
      password : form.password,
    })
    router.push('/');
  }catch(e) {
    errors.value = e.response.data.errors
  }
}

</script>

<template>
  <guest-layout>
    <div class="">
      <div class="p-12 mx-auto border rounded-lg w-full md:w-3/4 lg:w-1/3 shadow-lg bg-white">
        <h1 class="text-2xl text-center m-6 font-bold">SimpleTodo</h1>
        <form @submit.prevent="register()" class="space-y-4">
            <div>
                <label for="name" class="block mb-2 text-lg font-medium text-gray-900">Name <span class="text-red-500">*</span></label>
                <input type="name" v-model="form.name" name="name" id="name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="ユーザー名を入力してください" required>
                <div v-if="errors.name" class="text-red-400">{{ errors.name[0] }}</div>
            </div>
            <div>
                <label for="email" class="block mb-2 text-lg font-medium text-gray-900">Email <span class="text-red-500">*</span></label>
                <input type="email" v-model="form.email" name="email" id="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="メールアドレスを入力してください" required>
                <div v-if="errors.email" class="text-red-400">{{ errors.email[0] }}</div>
            </div>
            <div>
              <label for="password" class="block mb-2 text-lg font-medium text-gray-900">Password<span class="text-red-500"> *</span></label>
              <input type="password" v-model="form.password" name="password" id="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="パスワードを入力してください" required>
              <div v-if="errors.password" class="text-red-400">{{ errors.password[0] }}</div>
            </div>
            <div>
              <label for="password_confirmation" class="block mb-2 text-lg font-medium text-gray-900">Password Confirm<span class="text-red-500"> *</span></label>
              <input type="password" v-model="form.password_confirmation" name="password_confirmation" id="password_confirmation" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="確認用パスワードを入力してください" required>
              <div v-if="errors.password_confirmation" class="text-red-400">{{ errors.password_confirmation[0] }}</div>
            </div>
            <div class="flex justify-around">
              <button type="submit" class="w-1/2 mx-3 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Register</button>
            </div>
            <div>
              <router-link to="/login" class="text-blue-400 hover:text-blue-500">すでにアカウントをお持ちですか?ログイン</router-link>
            </div>
        </form>
      </div>
    </div>
  </guest-layout>
  
</template>
