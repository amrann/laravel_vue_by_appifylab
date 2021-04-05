import Vue from 'vue'
import Router from 'vue-router'
import firstVuePage from './components/pages/firstVuePage'
import secondVuePage from './components/pages/secondVuePage'
import hookPage from './components/pages/basic/hooks.vue'
import methodPage from './components/pages/basic/methods.vue'

// project pages
import homePage from './components/pages/home'
import tagPage from './admin/pages/tag'
import categoriPage from './admin/pages/categori'

Vue.use(Router)

const routes = [
    // project routes
    {
        path: '/',
        component: homePage
    },
    {
        path: '/tag',
        component: tagPage
    },
    {
        path: '/categori',
        component: categoriPage
    },



    // basic tutorial routes
    {
        path: '/first',
        component: firstVuePage
    },
    {
        path: '/second',
        component: secondVuePage
    },
    // vue hooks
    {
        path: '/hook',
        component: hookPage
    },
    // vue methods
    {
        path: '/method',
        component: methodPage
    }
]

export default new Router({
    mode: 'history',
    routes
})
