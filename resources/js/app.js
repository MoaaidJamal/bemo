import './bootstrap';
import 'vue-universal-modal/dist/index.css';
import VueUniversalModal from 'vue-universal-modal';
import {createApp} from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {fas} from "@fortawesome/free-solid-svg-icons";
import { faHatWizard } from '@fortawesome/free-solid-svg-icons'

library.add(faHatWizard)
library.add(fas)
const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.component('font-awesome-icon', FontAwesomeIcon)
app.mount("#app");
app.use(VueUniversalModal, {
    teleportTarget: '#modals',
});
