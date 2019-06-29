
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';

Vue.use(VueRouter);
Vue.use(Vuex);
const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes, 
    mode : 'history'
})

// Vue.component('articles', require('./components/Articles.vue'));
// Vue.component('navbar', require('./components/Navbar.vue'));



const app = new Vue({
    el: '#app',
    router,
    store,
    components : {
        MainApp
    }
});
