require('./bootstrap.js');

import Vue from 'vue';
import 'es6-promise/auto';
import axios from 'axios';
import VueAxios from 'vue-axios';
// import VueRouter from 'vue-router';
import VueAuth from '@websanova/vue-auth';

import auth from './auth.js';
import router from './router.js';
import App from './layouts/App.vue';



// Set vue globally
window.Vue = Vue;

//Vuetify Library
import Vuetify from 'vuetify';
import "vuetify/dist/vuetify.min.css"

Vue.use(Vuetify);
const opts = {};
const vuetify =  new Vuetify(opts)
//
// set Vue Router
Vue.router = router;
// Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`;

Vue.use(VueAuth, auth);

//Rich text editor
import { TiptapVuetifyPlugin } from 'tiptap-vuetify'
import 'tiptap-vuetify/dist/main.css'

Vue.use(TiptapVuetifyPlugin, 
  {
    vuetify, // same as "vuetify: vuetify"
    iconsGroup: 'md'
  })



  
// Load main Layout
Vue.component('index', App);

const app = new Vue({
    router,
    el: '#app',
    vuetify,
});

