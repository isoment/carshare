import VueRouter from 'vue-router';
import MainPage from './components/MainPage';
import Error404 from './shared/components/Error404';
import Login from './auth/Login';
import Register from './auth/Register';
import PasswordResetRequest from './auth/PasswordResetRequest';
import PasswordResetEmail from './auth/PasswordResetEmail';
import MainVehicleComponent from './vehicle/MainVehicleComponent';
import VehicleComponent from './vehicle/VehicleComponent';
import CustomerProfile from './dashboard/CustomerProfile';
import DriversLicense from './dashboard/DriversLicense';
import ShoppingCart from './cart/ShoppingCart';
import Payment from './cart/Payment';
import Confirmation from './cart/Confirmation';
import CustomerSharedVehicles from './dashboard/CustomerSharedVehicles';
import CustomerEditVehicle from './dashboard/CustomerEditVehicle';
import CustomerReviews from './dashboard/CustomerReviews';
import CustomerBookings from './dashboard/CustomerBookings';
import CustomerShowBooking from './dashboard/booking-components/CustomerShowBooking';

const routes = [
    {
        path: "/",
        component: MainPage,
        name: "main-page"
    },

    /** AUTHORIZATION **/
    /////////////////////
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

    /** VEHICLE **/
    ///////////////
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

    /** DASHBOARD **/
    /////////////////
    {
        path: "/dashboard/customer/profile",
        component: CustomerProfile,
        name: "customer-profile"
    },
    {
        path: "/dashboard/customer/license",
        component: DriversLicense,
        name: "drivers-license"
    },
    {
        path: "/dashboard/customer/shared-vehicles",
        component: CustomerSharedVehicles,
        name: "customer-vehicles"
    },
    {
        path: "/dashboard/customer/edit-vehicle/:id",
        component: CustomerEditVehicle,
        name: "customer-edit-vehicle"
    },
    {
        path: "/dashboard/customer/reviews",
        component: CustomerReviews,
        name: "customer-reviews"
    },
    {
        path: "/dashboard/customer/bookings",
        component: CustomerBookings,
        name: "customer-bookings"
    },
    {
        path: "/dashboard/customer/booking/:id",
        component: CustomerShowBooking,
        name: "customer-show-booking"
    },

    /** CART **/
    ////////////
    {
        path: "/cart",
        component: ShoppingCart,
        name: "shopping-cart"
    },
    {
        path: "/cart/payment",
        component: Payment,
        name: "payment"
    },
    {
        path: "/confirmation",
        component: Confirmation,
        name: "confirmation"
    },

    /** OTHER **/
    /////////////
    {
        path: "*",
        component: Error404,
        name: "error404"
    },
];

const router = new VueRouter({
    routes,

    mode: "history",
    
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

export default router;