
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.eventDispatcher = new Vue();
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('friends-view', require('./components/views/Friends.vue'));
Vue.component('friends-sidebar', require('./components/sidebar/Friends.vue'));
Vue.component('followers-sidebar', require('./components/sidebar/Followers.vue'));
Vue.component('friend-buttons', require('./components/friend-buttons/FriendButtons.vue'));
Vue.component('friend-requests-sent', require('./components/friend-request/Sent.vue'));
Vue.component('friend-requests-received', require('./components/friend-request/Received.vue'));


const app = new Vue({
    el: '#app'
});
