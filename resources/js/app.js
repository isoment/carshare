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

// Import vue observer visibility for infinite scroll
import VueObserveVisibility from 'vue-observe-visibility';

// This gives us access to a special Route object inside every component
Vue.use(VueRouter);
// Need to use vuex as a vue plugin
Vue.use(Vuex);
// For infinite scroll
Vue.use(VueObserveVisibility);

// Setup a new state
const store = new Vuex.Store(storeDefinition);

window.Vue = require('vue').default;

// Global Components
Vue.component('error-404', require('./shared/components/Error404.vue').default);
Vue.component('unauthorized', require('./shared/components/Unauthorized.vue').default);
Vue.component('main-navigation', require('./shared/components/NavigationBarMain.vue').default);
Vue.component('validation-errors', require('./shared/components/ValidationErrors').default);
Vue.component('notifications-list', require('./shared/components/NotificationsList').default);
Vue.component('star-rating', require('./shared/components/StarRating').default);

// Create root vue instance
const app = new Vue({
    el: '#app',

    // Add router
    router: router,

    // Add vuex store
    store,

    components: {
        'index': Index
    },

    beforeCreate() {
        this.$store.dispatch('loadStoredState');
        this.$store.dispatch('loadUser');
    }
});
