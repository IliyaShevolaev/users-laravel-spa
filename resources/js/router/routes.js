import App from "../components/App.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import Departments from "../components/DataTables/Departments/Departments.vue";
import Positions from "../components/DataTables/Positions/Positions.vue";
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
        path: '/departments',
        component: Departments,
        name: 'departments',
        meta: { requiresAuth: true },
    },

    {
        path: '/positions',
        component: Positions,
        name: 'positions',
        meta: { requiresAuth: true },
    },

    {
        path: '/ping',
        component: Ping,
    }
];

export default routes;
