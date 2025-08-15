import { onMounted } from "vue";
import { useAuthStore } from "../../stores/auth";

export function listenRoleEvent() {
    const authStore = useAuthStore();
    watch(
        () => authStore.userData.role?.id,
        (roleId) => {
            console.log("your role " + roleId);
            if (!roleId) return;
            window.Echo.private(`change.role.${roleId}`).listen(
                ".change.role.permissions",
                (event) => {
                    authStore.requestAuth();
                }
            );
        },
        { immediate: true }
    );
}
