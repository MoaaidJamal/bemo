import './bootstrap';
import 'vue-universal-modal/dist/index.css';
import VueUniversalModal from 'vue-universal-modal';
import {createApp} from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'

const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.mount("#app");
app.use(VueUniversalModal, {
    teleportTarget: '#modals',
});
