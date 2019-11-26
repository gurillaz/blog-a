import VueRouter from 'vue-router';
import Vue from 'vue';

// Pages
import ClientLayout from './js/Layouts/ClientLayout.vue';
import AdminLayout from './js/Layouts/AdminLayout.vue';
import ErrorPage from './js/pages/error_page.vue';

//Admin pages

import AdminHome from './js/pages/admin/admin_home.vue';
import AdminUsers from './js/pages/admin/admin_users.vue';
import AdminCategories from './js/pages/admin/admin_categories.vue';
import AdminComments from './js/pages/admin/admin_comments.vue';






// Client Pages
import Home from './js/pages/home.vue';
import Login from './js/components/Login.vue';
import Register from './js/components/Register.vue';
import ForgotPassword from './js/components/ForgotPassword.vue';
import ForgotPasswordForm from './js/components/ForgotPasswordForm.vue';

import Profile from './js/pages/profile_dashboard.vue';
import Search from './js/pages/search.vue';
import Category from './js/pages/category.vue';
import Tag from './js/pages/tag.vue';
import User from './js/pages/user.vue';


import Article from './js/pages/article/article.vue';
import Articles from './js/pages/article/article_all.vue';
import ArticleNew from './js/pages/article/article_new.vue';
import ArticleEdit from './js/pages/article_edit.vue';


Vue.use(VueRouter);
// Our routes
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin', component: AdminLayout,
            meta: {

                auth: { roles:'admin', redirect: '/login' },
            },

            children: [
                {
                    path: '/',
                    name: 'article.base',

                    beforeEnter: (to, from, next) => {
                        next('/admin/home')
                    },
                },
                {
                    path: 'home',
                    name: 'admin.home',
                    component: AdminHome,
                }, {
                    path: 'users',
                    name: 'admin.users',
                    component: AdminUsers,
                }, {
                    path: 'categories',
                    name: 'admin.categories',
                    component: AdminCategories,

                }, {
                    path: 'comments',
                    name: 'admin.comments',
                    component: AdminComments,
                },
            ]
        },
        {
            path: '/', component: ClientLayout,
            children: [
                {
                    path: '/',
                    name: 'home',
                    component: Home,
                }, {
                    path: 'profile',
                    name: 'profile',
                    component: Profile,
                },
                {
                    path: 'tag/:id',
                    name: 'tag',
                    component: Tag,

                    props: true
                },
                {
                    path: 'user/:id',
                    name: 'user',
                    component: User,

                    props: true
                },
                {
                    path: 'search',
                    name: 'search',
                    component: Search,

                },
            ]
        },
        {
            path: '/category', component: ClientLayout,
            children: [
                {
                    path: ':id',
                    name: 'category.category',
                    component: Category,

                    props: true
                },
            ]
        },
        {
            path: '/article', component: ClientLayout,
            children: [
                {
                    path: '/',
                    name: 'article.base',

                    beforeEnter: (to, from, next) => {
                        next('/article/all')
                    }
                },
                {
                    path: 'all',
                    name: 'article.all',
                    component: Articles,
                    // beforeEnter: requireAuth
                },
                {
                    path: 'new',
                    name: 'article.new',

                    component: ArticleNew
                },
                {
                    path: ':slug',
                    name: 'article.article',
                    component: Article,

                    props: true
                }, {
                    path: ':id/edit',
                    name: 'article.edit',
                    component: ArticleEdit,

                    props: true
                },
            ]
        },

        // {
        //     path: '/article/:slug',
        //     name: 'article',
        //     component: Article,
        //     props: true

        // },

        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                auth: false // The auth property specifies whether authorization is needed to access the route.
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false
            }
        },

        {
            path: '/reset-password',
            name: 'reset-password',
            component: ForgotPassword,
            meta: {
                auth: false
            }
        },
        {
            path: '/reset-password/:token',
            name: 'reset-password-form',
            component: ForgotPasswordForm,
            meta: {
                auth: false
            }
        },






        {
            path: '/*',
            name: 'main.error',
            component: ErrorPage
        }


    ],

});



export default router;