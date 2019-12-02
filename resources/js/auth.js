import bearer from '@websanova/vue-auth/drivers/auth/bearer';
import axios from '@websanova/vue-auth/drivers/http/axios.1.x';
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x';

/**
 * Authentication configuration, some of the options can be override in method calls
 */
const config = {
    auth: bearer,
    http: axios,
    rolesVar: 'role',
    router: router,
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStorage: ['localStorage'],

    // API endpoints used in Vue Auth
    registerData: {
        url: '/guest/register',
        method: 'POST',
        redirect: '/login'
    },
    loginData: {
        url: '/guest/login',
        method: 'POST',
        redirect: '/',
        fetchuser: true
    },



    logoutData: {
        url: '/auth/logout',
        method: 'POST',
        redirect: '/login',
        makeRequest: true
    },
    fetchData: {
        url: '/auth/user',
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: '/guest/refresh',
        method: 'GET',
        enabled: true,
        interval: 30
    }
}

export default config;