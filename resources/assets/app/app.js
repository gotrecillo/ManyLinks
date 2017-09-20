// npm imports
import Vue from 'vue'
import Vuetify from 'vuetify'

// our imports
import App from './App.vue';

Vue.use(Vuetify);
Vue.config.devtools = true;


console.log({ ...App });

const app = new Vue({
    ...App
});

export { app };