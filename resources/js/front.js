import App from './components/App.vue';

require('./bootstrap');

window.Vue = require('vue');

const root = new Vue({
    el: '#root',
    render: h => h(App)
})