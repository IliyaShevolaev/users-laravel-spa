import { onMounted, onBeforeUnmount, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";

export function listenUserRoleChangesEvent() {
    const authStore = useAuthStore();
    const echoChannel = ref(null);

    const listenUser = (userId) => {
        if (!userId) return;

        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.user.role");
            echoChannel.value = null;
        }

        echoChannel.value = window.Echo.private(`change.user.role.${userId}`).listen(
            ".change.user.role",
            (event) => {
                console.log("Your role changed!!!!");
                authStore.requestAuth();
            }
        );
    };

    listenUser(authStore.userData.id);

    watch(
        () => authStore.userData.id,
        (userId) => {
            listenUser(userId);
        }
    );

    onBeforeUnmount(() => {
        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.user.role");
            echoChannel.value = null;
        }
    });
}
