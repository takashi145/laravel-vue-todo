<script setup>
import Header from '../Components/Header.vue';
</script>

<template>
  <div>
    <Header />
    <div class="m-8">
      <slot />
    </div>
  </div>
</template>
