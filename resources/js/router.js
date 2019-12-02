import VueRouter from 'vue-router';
import Vue from 'vue';

// Layouts and error page
import ClientLayout from './Layouts/ClientLayout.vue';
import AdminLayout from './Layouts/AdminLayout.vue';
import ErrorPage from './views/common/error_page.vue';



//Admin pages
import AdminHome from './views/admin/admin_home.vue';
import AdminUsers from './views/admin/admin_users.vue';
import AdminCategories from './views/admin/admin_categories.vue';
import AdminComments from './views/admin/admin_comments.vue';
import AdminArticles from './views/admin/admin_articles.vue';
import AdminTags from './views/admin/admin_tags.vue';
import AdminPendingArticles from './views/admin/admin_articles_pending.vue';
import AdminPendingComments from './views/admin/admin_comments_pending.vue';
import AdminFeaturedOrder from './views/admin/admin_featured_order.vue';
import AdminUser from './views/admin/admin_user.vue';

import AdminArticle from './views/admin/admin_article.vue';
import AdminActivity from './views/admin/admin_activity.vue';


// Guest pages
import Home from './views/guest/home.vue';
import Login from './views/guest/Login.vue';
import Register from './views/guest/Register.vue';
import ForgotPassword from './views/guest/ForgotPassword.vue';
import ForgotPasswordForm from './views/guest/ForgotPasswordForm.vue';
import Search from './views/guest/search.vue';
import Category from './views/guest/category.vue';
import Tag from './views/guest/tag.vue';
import User from './views/guest/user.vue';
import Article from './views/guest/article.vue';


//Auth pages
import Profile from './views/auth/profile_dashboard.vue';
import ArticleNew from './views/auth/article_new.vue';
import ArticleEdit from './views/auth/article_edit.vue';


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
                    path: 'user/:id',
                    name: 'admin.user',
                    component: AdminUser,
                    props: true
                }, {
                    path: 'article/:id',
                    name: 'admin.article',
                    component: AdminArticle,
                    props: true
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
                }, {
                    path: 'pending_comments',
                    name: 'admin.comments_pending',
                    component: AdminPendingComments,
                }, {
                    path: 'featured_order',
                    name: 'admin.featured_order',
                    component: AdminFeaturedOrder,
                }, {
                    path: 'activity',
                    name: 'admin.activity',
                    component: AdminActivity,
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