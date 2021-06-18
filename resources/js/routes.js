import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';
import Login from './auth/Login';
import Register from './auth/Register';
import PasswordResetRequest from './auth/PasswordResetRequest';
import PasswordResetEmail from './auth/PasswordResetEmail';
import MainVehicleComponent from './vehicle/MainVehicleComponent';
import VehicleComponent from './vehicle/VehicleComponent';

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
        path: "/auth/password-reset-email",
        component: PasswordResetEmail,
        name: "password-reset-email"
    },
    {
        path: "/vehicles",
        component: MainVehicleComponent,
        name: "main-vehicle"
    },
    {
        path: "/vehicle/:id",
        component: VehicleComponent,
        name: "vehicle"
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