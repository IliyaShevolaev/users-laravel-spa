import { useAuthStore } from "../../stores/auth";

export function setUpRouteGuards(router) {
    router.beforeEach(async (to, from, next) => {
        const authStore = useAuthStore();

        if (!localStorage.getItem("auth") && to.meta.requiresAuth) {
            return next("/login");
        }

        if (localStorage.getItem("auth") && to.meta.requiresNoAuth) {
            return next("/");
        }

        if (authStore.isLoading) {
            await authStore.requestAuth();
        }

        if (
            to.meta.permission &&
            !authStore.checkPermission(to.meta.permission)
        ) {
            return next("/403");
        }

        next();
    });
}
