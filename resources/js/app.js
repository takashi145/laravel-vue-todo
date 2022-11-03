import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import { store } from './store'
import App from "./App.vue";
import router from '@/router'

store.dispatch('getUser')
.then(() => {
  createApp(App)
  .use(router)
  .use(store)
  .mount("#app");
});
