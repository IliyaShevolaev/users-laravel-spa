export const routes = [
    { path: "/", redirect: "/users" },
    {
        path: "/",
        component: () => import("@/layouts/default.vue"),
        meta: { requiresAuth: true },
        children: [
            {
                path: "ping",
                component: () => import("@/pages/Ping.vue"),
            },
            {
                path: "ws",
                component: () => import("@/pages/ws.vue"),
            },
            {
                path: "users",
                component: () => import("@/pages/users.vue"),
                meta: { permission: 'users-read' },
            },
            {
                path: "departments",
                component: () => import("@/pages/departments.vue"),
                meta: { permission: 'departments-read' },
            },
            {
                path: "positions",
                component: () => import("@/pages/positions.vue"),
                meta: { permission: 'positions-read' },
            },
            {
                path: "roles",
                component: () => import("@/pages/roles.vue"),
                meta: { permission: 'roles-read' },
            },
            {
                path: "roles/create",
                component: () => import("@/pages/rolesCreate.vue"),
                meta: { permission: 'roles-create' },
            },
            {
                path: "roles/edit/:id",
                component: () => import("@/pages/rolesCreate.vue"),
                props: true,
                meta: { permission: 'roles-update' },
            },
            {
                path: "/:pathMatch(.*)*",
                component: () => import("@/pages/[...error].vue"),
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
                meta: { requiresNoAuth: true },
            },
            {
                path: "register",
                component: () => import("@/pages/register.vue"),
                meta: { requiresNoAuth: true },
            },
        ],
    },
];
