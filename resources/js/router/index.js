import { createRouter, createWebHistory } from "vue-router";

import Index from '../views/Index.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Index
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    next()
})

export default router
