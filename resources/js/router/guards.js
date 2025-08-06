export function setUpRouteGuards(router) {
    router.beforeEach((to, from, next) => {
        if (localStorage.getItem('auth') === null && to.meta.requiresAuth) {
            next('/login')
        } else if (localStorage.getItem('auth') !== null && to.meta.requiresNoAuth){
            next('/')
        } else {
            next();
        }
    });
}
