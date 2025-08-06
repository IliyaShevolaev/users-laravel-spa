import { useAuthStore } from "../stores/auth";

export function setUpRouteGuards(router) {
    router.beforeEach((to, from, next) => {
        if (!useAuthStore().isAuth && to.meta.requiresAuth) {
            next('/login')
        } else {
            next();
        }
    });
}
