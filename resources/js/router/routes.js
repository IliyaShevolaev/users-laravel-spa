import App from "../components/App.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import Departments from "../components/DataTables/Departments/Departments.vue";
import Positions from "../components/DataTables/Positions/Positions.vue";
import Users from "../components/DataTables/Users/Users.vue";

const routes = [
    {
        path: "/",
        component: Users,
        name: 'app',
    },

    {
        path: "/login",
        component: Login,
        name: 'login',
        meta: { requiresNoAuth: true },
    },

    {
        path: "/register",
        component: Register,
        name: 'register',
        meta: { requiresNoAuth: true },
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
        path: '/users',
        component: Users,
        name: 'users',
        meta: { requiresAuth: true },
    },
];

export default routes;
