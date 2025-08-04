import App from "../components/App.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import Dashboard from "../components/pages/Dashboard.vue";

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
    }
];

export default routes;
