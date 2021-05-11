import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';
import Login from './auth/Login';
import Register from './auth/Register';
import PasswordResetRequest from './auth/PasswordResetRequest';
import PasswordReset from './auth/PasswordReset';

const routes = [
    {
        path: "/",
        component: MainPage,
        name: "main-page"
    },
    {
        path: "/auth/login",
        component: Login,
        name: "login"
    },
    {
        path: "/auth/register",
        component: Register,
        name: "register"
    },
    {
        path: "/auth/password-reset-request",
        component: PasswordResetRequest,
        name: "password-reset-request"
    },
    {
        path: "/auth/password-reset/:email",
        component: PasswordReset,
        name: "password-reset"
    },
    {
        path: "*",
        component: Error404,
        name: "error404"
    },
];

const router = new VueRouter({
    routes,
    mode: "history",
});

export default router;