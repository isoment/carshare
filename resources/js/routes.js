import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';
import Login from './auth/Login';
import Register from './auth/Register';

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