<template>
    <div>
        <div
            class="messages"
            style="
                height: 200px;
                overflow-y: auto;
                border: 1px solid #ccc;
                padding: 10px;
            "
        >
            <div v-for="(msg, index) in messages" :key="index">
                <strong>{{ msg.username }}:</strong> {{ msg.message }}
            </div>
        </div>

        <input
            v-model="username"
            placeholder="Ваш ник"
            style="width: 100%; margin-top: 5px"
        />
        <input
            v-model="newMessage"
            @keyup.enter="sendMessage"
            placeholder="Сообщение..."
            style="width: 100%; margin-top: 5px"
        />
        <button @click="sendMessage" style="margin-top: 5px">Отправить</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const username = ref("");
const newMessage = ref("");
const messages = ref([]);

onMounted(() => {
    window.Echo.channel('chat')
        .listen('.message.sent', (event) => {
            console.log('Событие пришло:', event);
            messages.value.push({
                username: event.username,
                message: event.message,
            });
        });
});

function sendMessage() {
    if (!username.value.trim() || !newMessage.value.trim()) return;

    axios.post("/api/chat/send", {
        username: username.value,
        message: newMessage.value,
    });

    messages.value.push({
        username: username.value,
        message: newMessage.value,
    });

    newMessage.value = "";
}
</script>
