import App from "../components/App.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import Dashboard from "../components/pages/Dashboard.vue";
import Ping from "../components/Ping.vue";

const routes = [
    {
        path: "/",
        component: Dashboard,
        name: 'app',
    },

    {
        path: "/login",
        component: Login,
        name: 'login',
    },

    {
        path: "/register",
        component: Register,
        name: 'register',
    },

    {
        path: '/dashboard',
        component: Dashboard,
        name: 'dashboard',
    },

    {
        path: '/ping',
        component: Ping,
    }
];

export default routes;
