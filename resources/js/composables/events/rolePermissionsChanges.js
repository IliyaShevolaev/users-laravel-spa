import { onMounted, onBeforeUnmount, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";

export function listenRolePermissionChangesEvent() {
    const authStore = useAuthStore();
    const echoChannel = ref(null);

    const listenRoleEvent = (roleId) => {
        if (!roleId) return;

        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.role.permissions");
            echoChannel.value = null;
        }

        echoChannel.value = window.Echo.private(`change.role.${roleId}`).listen(
            ".change.role.permissions",
            (event) => {
                console.log("Your permissions changed!!!!");
                authStore.requestAuth();
            }
        );
    };

    listenRoleEvent(authStore.userData.role?.id);

    watch(
        () => authStore.userData.role?.id,
        (roleId) => {
            listenRoleEvent(roleId);
        }
    );

    onBeforeUnmount(() => {
        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.role.permissions");
            echoChannel.value = null;
        }
    });
}
