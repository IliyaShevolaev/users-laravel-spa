import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useEventNotifyStore } from "../../stores/eventNotifies";

export function listenCalendarChangesEvent(changeFunction = null) {
    const authStore = useAuthStore();
    const eventNotifyStore = useEventNotifyStore();
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
            console.log(event);
            if (event.event.creator.id !== authStore.userData.id) {
                console.log("Your event changed!!!!");
                console.log(event);
                if (changeFunction) {
                    changeFunction();
                }
                if (event.isNewAssign) {
                    console.log("NOTIFY");
                    eventNotifyStore.pushNewEvent(event.event);
                }
            }
        });
    };

    onMounted(() => {
        listenUser(authStore.userData.id);
    });

    watch(
        () => authStore.userData.id,
        (userId) => {
            listenUser(userId);
        }
    );
}
