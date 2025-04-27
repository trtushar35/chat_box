<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    selectedUser: Object
});

const message = ref('');
const messages = ref([]);

const sendMessage = () => {
    if (message.value.trim()) {
        messages.value.push({ text: message.value, sender: 'you' });

        // Dummy reply from user after 1 second
        setTimeout(() => {
            messages.value.push({ text: 'Got your message!', sender: 'them' });
        }, 1000);

        message.value = '';
    }
};

watch(() => props.selectedUser, (newUser) => {
    messages.value = [];
});
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="bg-gray-800 text-white p-4 flex items-center">
            <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                <span class="text-white font-bold text-lg">{{ selectedUser?.name?.charAt(0) }}</span>
            </div>
            <h2 class="text-lg font-semibold">{{ selectedUser?.name || 'Select a user' }}</h2>
        </div>

        <div class="flex-1 p-6 overflow-auto bg-gray-50 space-y-4">
            <div v-for="(msg, index) in messages" :key="index" class="flex" :class="msg.sender === 'you' ? 'justify-end' : 'justify-start'">
                <div :class="msg.sender === 'you' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-800'"
                    class="rounded-2xl p-3 max-w-xs break-words shadow-md">
                    {{ msg.text }}
                </div>
            </div>
        </div>

        <div class="p-4 border-t bg-white flex gap-2">
            <input v-model="message" type="text"
                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                placeholder="Type a message..." @keyup.enter="sendMessage" />
            <button @click="sendMessage"
                class="bg-blue-500 text-white px-5 rounded-full hover:bg-blue-600 transition shadow-sm">
                Send
            </button>
        </div>
    </div>
</template>
