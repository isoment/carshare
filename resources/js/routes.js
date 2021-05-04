import VueRouter from 'vue-router';
import ExampleComponent from './components/ExampleComponent';
import Error404 from './shared/components/Error404';

const routes = [
    {
        path: "/",
        component: ExampleComponent,
        name: "home",
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