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
                path: "activity-logs/:id",
                component: () => import("@/pages/activityLogs.vue"),
                props: route => ({ id: Number(route.params.id) }),
                meta: { permission: 'users-logs' },
            },
            {
                path: "roles/create",
                component: () => import("@/pages/rolesCreate.vue"),
                meta: { permission: 'roles-create' },
            },
            {
                path: "roles/edit/:id",
                component: () => import("@/pages/rolesCreate.vue"),
                props: route => ({ id: Number(route.params.id) }),
                meta: { permission: 'roles-update' },
            },
            {
                path: "tasks/calendar",
                component: () => import("@/pages/tasksCalendar.vue"),
                meta: { permission: 'tasks-read' },
            },
            {
                path: "tasks/stats",
                component: () => import("@/pages/tasksStats.vue"),
                meta: { permission: 'tasks-stats' },
            },
            {
                path: "cities",
                component: () => import("@/pages/cities.vue"),
                meta: { permission: 'tasks-stats' },
            },
            {
                path: "regions",
                component: () => import("@/pages/regions.vue"),
                meta: { permission: 'tasks-stats' },
            },
            {
                path: "files/templates",
                component: () => import("@/pages/fileTemplates.vue"),
                meta: { permission: 'tasks-stats' },
            },
            {
                path: "files/user/storage/:id",
                component: () => import("@/pages/userStorage.vue"),
                meta: { permission: 'fileTemplates-read' },
                props: route => ({ id: Number(route.params.id) }),
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
