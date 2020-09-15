import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router)

export default new Router({
    routes:[
        {
            path: '/',
            name: 'home',
            component: require('./views/Home').default
        },
        {
            path: '/blog',
            name: 'blog',
            component: require('./views/Blog').default
        },
        {
            path: '/notas',
            name: 'notas',
            component: require('./views/Nota').default
        },
        {
            path: '/calendario',
            name: 'calendario',
            component: require('./views/Calendario').default
        },
        {
            path: ':slug', //Para pasar parametros
            name: 'post',
            component: require('./views/Post').default,
            props: true //Vamos a pasar propiedades o parametros
        },
        {
            path: '*',
            component: require('./views/404').default
        },
    ],
    mode: 'history' //Elimina el # (hash) de la URL
})