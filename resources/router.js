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
import AdminArticles from './js/pages/admin/admin_articles.vue';
import AdminTags from './js/pages/admin/admin_tags.vue';
import AdminPendingArticles from './js/pages/admin/admin_articles_pending.vue';
import AdminPendingComments from './js/pages/admin/admin_comments_pending.vue';






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
import ArticleNew from './js/pages/article/article_new.vue';
import ArticleEdit from './js/pages/article_edit.vue';


Vue.use(VueRouter);
// Our routes
const router = new VueRouter({
    mode: 'history',
    routes: [

        //Non auth routes
        {
            path: '/', component: ClientLayout,
            children: [

                {
                    path: '/',
                    name: 'home',
                    component: Home,
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

                {
                    path: 'category/:id',
                    name: 'category.category',
                    component: Category,

                    props: true
                },
                {
                    path: 'tag/:id',
                    name: 'tag',
                    component: Tag,

                    props: true
                },
                //Auth routes
                {
                    path: 'profile',
                    name: 'profile',
                    component: Profile,
                    meta: {

                        auth: true,
                    },
                },


            ]
        },
        {
            path: '/article', component: ClientLayout,
            children: [


                {
                    path: 'new',
                    name: 'article.new',

                    component: ArticleNew,
                    meta: {

                        auth: true,
                    },
                },

                {
                    path: ':slug',
                    name: 'article.article',
                    component: Article,
                    // meta: {

                    //     auth: false,
                    // },
                    props: true

                },
                {
                    path: ':id/edit',
                    name: 'article.edit',
                    component: ArticleEdit,
                    meta: {

                        auth: true,
                    },

                    props: true
                },
            ]
        },
        // User registration and authentication routes
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







        //Admin routes
        {
            path: '/admin', component: AdminLayout,
            meta: {

                auth: { roles: 'admin', redirect: '/login' },
            },

            children: [
                {
                    path: '/',
                    name: 'home.base',

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
                {
                    path: 'articles',
                    name: 'admin.articles',
                    component: AdminArticles,
                },
                 {
                    path: 'tags',
                    name: 'admin.tags',
                    component: AdminTags,
                }, {
                    path: 'pending_articles',
                    name: 'admin.articles_pending',
                    component: AdminPendingArticles,
                },{
                    path: 'pending_comments',
                    name: 'admin.comments_pending',
                    component: AdminPendingComments,
                },
            ]
        },






        {
            path: '/*',
            name: 'main.error',
            component: ErrorPage
        }


    ],

});



export default router;