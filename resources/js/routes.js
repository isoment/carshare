import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';

const routes = [
    {
        path: "/",
        component: MainPage,
        name: "main-page",
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