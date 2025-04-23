import './bootstrap'
import {createApp} from 'vue'
// // import IndexComponent from './pages/IndexComponent.vue'
import AppTemplate from '../js/pages/AppTemplate.vue';
import { pinia } from '../js/stores';
// /*
//  * Vendors/Plugins
// */
import { library } from '@fortawesome/fontawesome-svg-core' /* import the fontawesome core | npm i @fortawesome/fontawesome-svg-core*/
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome' /* import font awesome icon component | npm i @fortawesome/vue-fontawesome*/
import { fas } from '@fortawesome/free-solid-svg-icons' /* import entire style | npm i @fortawesome/free-solid-svg-icons*/
library.add(fas) /* add icons to the library */
// //VueSelect
import MultiselectElement from '@vueform/multiselect'
import 'vue-toast-notification/dist/theme-bootstrap.css'

// /* Startbootstrap-sb-admin template */
import "startbootstrap-sb-admin/dist/css/styles.css";
import "startbootstrap-sb-admin/dist/js/scripts.js";
import "startbootstrap-sb-admin/dist/js/datatables-simple-demo.js"
/* Local JS extensions */
import router from "../js/routes";

createApp(AppTemplate)
.use(pinia)
.use(router)
.component('font-awesome-icon',FontAwesomeIcon)
.component('MultiselectElement',MultiselectElement)
.mount('#app');
