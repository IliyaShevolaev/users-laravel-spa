import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";

export function listenCalendarChangesEvent(changeFunction) {
    const authStore = useAuthStore();
    const echoChannel = ref(null);

    const stopListenChannel = function () {
        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.calendar.events");
            echoChannel.value = null;
        }
    };

    const listenUser = (userId) => {
        if (!userId) return;

        stopListenChannel();

        echoChannel.value = window.Echo.private(
            `change.calendar.events.${userId}`
        ).listen(".change.calendar.events", (event) => {
            console.log("Your event changed!!!!");
            changeFunction();
        });
    };

    onMounted(() => {
        listenUser(authStore.userData.id);
    })

    watch(
        () => authStore.userData.id,
        (userId) => {
            listenUser(userId);
        }
    );

    onBeforeUnmount(() => {
        stopListenChannel();
    });
}
