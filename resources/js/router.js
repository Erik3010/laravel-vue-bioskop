import Vue from 'vue';
import VueRouter from 'vue-router';

// components
import Branch from './components/Branch.vue';
import Login from './components/Login.vue';
import Studio from './components/Studio.vue';
import Movie from './components/Movie.vue';
import Schedule from './components/Schedule.vue';

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Branch
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/studio',
        component: Studio
    },
    {
        path: '/movie',
        component: Movie
    },
    {
        path: '/schedule',
        component: Schedule
    }
]

const router = new VueRouter({
    routes,
    mode: 'history'
})

export default router