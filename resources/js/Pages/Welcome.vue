<script setup>
import GuestLayoutVue from "../Layouts/GuestLayout.vue";
</script>
<template>
  <GuestLayout>
    <div class="text-center min-h-screen mt-48">
      <h1 class="text-5xl mb-12">Simple Todo App</h1>
      <router-link to="/login" class="p-3 rounded text-white bg-blue-400 hover:bg-blue-500">始める</router-link>
    </div>
  </GuestLayout>
</template>