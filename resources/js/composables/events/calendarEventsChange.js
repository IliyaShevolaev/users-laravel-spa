import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useEventNotifyStore } from "../../stores/eventNotifies";

function useCalendarListener(onEvent) {
    const authStore = useAuthStore();
    const echoChannel = ref(null);

    const stopListenChannel = () => {
        if (echoChannel.value) {
            echoChannel.value.stopListening(".change.calendar.events");
            echoChannel.value = null;
        }
    };

    const listenUser = (userId) => {
        if (!userId) return;

        stopListenChannel();

        echoChannel.value = window.Echo
            .private(`change.calendar.events.${userId}`)
            .listen(".change.calendar.events", (event) => {
                if (event.event.creator.id !== authStore.userData.id) {
                    onEvent(event);
                }
            });
    };

    listenUser(authStore.userData.id);

    watch(() => authStore.userData.id, (userId) => listenUser(userId));
}

export function listenCalendarNotifications() {
    const eventNotifyStore = useEventNotifyStore();

    useCalendarListener((event) => {
        if (event.isNewAssign) {
            eventNotifyStore.pushNewEvent(event.event);
        }
    });
}

export function listenCalendarUpdates(changeFunction) {
    useCalendarListener((event) => {
        if (changeFunction) {
            changeFunction();
        }
    });
}
