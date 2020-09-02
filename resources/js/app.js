require('./bootstrap');

window.Vue = require('vue');

Vue.component('app', require('./components/AppComponent.vue').default);
Vue.component('posts', require('./components/PostsComponent.vue').default);
Vue.component('notas', require('./components/NotasComponent.vue').default);
Vue.component('infiniteLoading', require('vue-infinite-loading').default);

import router from './routes'

const app = new Vue({
    el: '#app',
    router
});
