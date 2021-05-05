/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Import Vue Router
import router from './routes';
import VueRouter from 'vue-router';

// Index component
import Index from './Index';

// Import Vue
import Vue from 'vue';

// Import Vuex, we'll also set a seperate file where we have it store.js
import Vuex from 'vuex';
import storeDefinition from './store';

// This gives us access to a special Route object inside every component
Vue.use(VueRouter);
// Need to use vuex as a vue plugin
Vue.use(Vuex);

// Setup a new state
const store = new Vuex.Store(storeDefinition);

window.Vue = require('vue').default;

// Global Components
Vue.component('error-404', require('./shared/components/Error404.vue').default);
Vue.component('main-navigation', require('./shared/components/NavigationBarMain.vue').default);

// Create root vue instance
const app = new Vue({
    el: '#app',

    // Add router
    router: router,

    // Add vuex store
    store,

    components: {
        'index': Index
    }
});
