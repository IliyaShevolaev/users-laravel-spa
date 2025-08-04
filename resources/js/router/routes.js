import App from "../components/App.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import Dashboard from "../components/pages/Dashboard.vue";
import Ping from "../components/Ping.vue";

const routes = [
    {
        path: "/login",
        component: Login,
    },

    {
        path: "/register",
        component: Register,
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
