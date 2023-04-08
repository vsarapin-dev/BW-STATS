import Vue from "vue";
import router from "./router";
import vuetify from "./plugins/vuetify";
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';

require('./bootstrap');
Vue.use(VueLoaders);

import Index from "./components/Index";

new Vue({
    el: '#app',
    components: {
         Index,
     },
    router,
    vuetify,
});
