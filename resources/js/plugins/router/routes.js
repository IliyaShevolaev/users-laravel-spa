export const routes = [
    { path: "/", redirect: "/users" },
    {
        path: "/",
        component: () => import("@/layouts/default.vue"),
        children: [
            {
                path: "ping",
                component: () => import("@/pages/Ping.vue"),
            },
            {
                path: "users",
                component: () => import("@/pages/users.vue"),
            },
            {
                path: "departments",
                component: () => import("@/pages/departments.vue"),
            },
            {
                path: "positions",
                component: () => import("@/pages/positions.vue"),
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/blank.vue"),
        children: [
            {
                path: "login",
                component: () => import("@/pages/login.vue"),
            },
            {
                path: "register",
                component: () => import("@/pages/register.vue"),
            },
            {
                path: "/:pathMatch(.*)*",
                component: () => import("@/pages/[...error].vue"),
            },
        ],
    },
];
