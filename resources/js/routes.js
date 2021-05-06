import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';
import Login from './auth/Login';

const routes = [
    {
        path: "/",
        component: MainPage,
        name: "main-page",
    },
    {
        path: "/login",
        component: Login,
        name: "login"
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