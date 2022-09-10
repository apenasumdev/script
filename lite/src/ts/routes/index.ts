import Vue from "vue"
import VueRouter, { RouteConfig } from "vue-router"
import guard from 'vue-router-multiguard'
import {guest, auth,logout} from '@/middlewares'
//Fixing Duplication Error
const originalPush = VueRouter.prototype.push;
VueRouter.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
};

const ADMIN_PREFIX = 'admin'

//Using Vue Router
Vue.use(VueRouter)


//Landing Page
import HomeLayout from '@/layouts/HomeLayout.vue'
import Home from '@/pages/Home.vue'

const routes: Array<RouteConfig> = [
    {
        path: "/",
        component: HomeLayout,
        children: [
            {
                path: '',
                name: 'home',
                meta: {layout: 'default'},
                component: Home
            },
            {
                path: "video/:id",
                name: "video",
                meta: {layout: 'default'},
                component: Home
            }
        ]
    },
    {
        path: "/page/:slug",
        name: "page",
        meta: {layout: 'default'},
        component: ()=> import(/* webpackChunkName: "page" */  '@/pages/Page.vue')
    },
    //404 Page
    {
        path: '/404',
        name: 'Page404',
        meta: {layout: 'default'},
        component: ()=> import(/* webpackChunkName: "error.404" */  '@/pages/Page404.vue')
    },
    //Redirect to Login
    {
      path: `/${ADMIN_PREFIX}`,
      name: 'admin',
      redirect: `/${ADMIN_PREFIX}/login`,
      component: ()=>  import(/* webpackChunkName: "admin.layout" */  '@/layouts/AdminLayout.vue'),
      children: [
          {
              path: `dashboard`,
              name: "admin.dashboard",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.dashboard" */  '@/pages/Admin/Dashboard.vue')
          },
          {
              path: `settings`,
              name: "admin.settings",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.settings" */  '@/pages/Admin/Settings.vue')
          },
          {
              path: `proxies`,
              name: "admin.proxies",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.proxies_list" */  '@/pages/Admin/Proxies/Index.vue')
          },
          {
              path: `codes`,
              name: "admin.code",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.codes" */  '@/pages/Admin/Codes.vue')
          },
          {
              path: `pages`,
              name: "admin.pages.list",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.pages_list" */  '@/pages/Admin/Pages/List.vue')
          },
          {
              path: `pages/:action/:slug?`,
              name: "admin.pages.modify",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.pages_create" */  '@/pages/Admin/Pages/Create.vue')
          },
          {
              path: `menu`,
              name: "admin.menu",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.menu" */  '@/pages/Admin/Menu.vue')
          },
          {
              path: `account`,
              name: "admin.account",
              meta: {layout: 'default', group:'admin'},
              beforeEnter: guard([auth]),
              component: ()=> import(/* webpackChunkName: "admin.account" */  '@/pages/Admin/Account.vue')
          },
      ]
    },
    //Login Route
    {
        path: `/${ADMIN_PREFIX}/login`,
        name: "admin.login",
        meta: {layout: 'empty-centered'},
        beforeEnter: guard([guest]),
        component: ()=> import(/* webpackChunkName: "admin.login" */  '@/pages/Auth/Login.vue')
    },
    //Admin Routes

    {
        path: '/*',
        redirect: {name: 'Page404'}
    }
]

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
})

router.beforeEach((to, from, next) => {
    window.smoothScroll();
    next();
});


export default router;
