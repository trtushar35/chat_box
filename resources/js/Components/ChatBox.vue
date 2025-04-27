<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

const isChatOpen = ref(false);
const message = ref('');
const messages = ref([]);
const isHovered = ref(false);
const isTyping = ref(false);
let shakeInterval = null;
let typingTimeout = null;
const scrollRef = ref(null);

// Function to scroll chat to bottom
const scrollToBottom = () => {
    nextTick(() => {
        if (scrollRef.value) {
            scrollRef.value.scrollTop = scrollRef.value.scrollHeight;
        }
    });
};

// Fetch messages from Laravel when chat opens
const fetchMessages = async () => {
    try {
        const res = await axios.get('/chat/messages');
        messages.value = res.data.map(msg => ({
            text: msg.message,
            sender: msg.sender_id === 1 ? 'sender' : 'receiver',
            timestamp: new Date(msg.created_at).toLocaleTimeString()
        }));
        scrollToBottom();
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
};

// Send message to Laravel
const sendMessage = async () => {
    if (!message.value.trim()) return;

    const newMessage = {
        text: message.value,
        sender: 'sender',
        timestamp: new Date().toLocaleTimeString()
    };

    messages.value.push(newMessage);
    scrollToBottom();

    try {
        await axios.post('/chat/send', { message: message.value });
    } catch (error) {
        console.error('Error sending message:', error);
    }

    message.value = '';
};

// Typing indicator logic
const handleTyping = () => {
    isTyping = true;
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        isTyping = false;
    }, 1000);
};

// Shake button effect
const triggerShake = () => {
    if (!isChatOpen.value && !isHovered.value) {
        const button = document.querySelector('.chat-button');
        if (button) {
            button.style.animationPlayState = 'running';
            setTimeout(() => {
                button.style.animationPlayState = 'paused';
            }, 2000);
        }
    }
};

// On mount, fetch messages and start shake effect
onMounted(() => {
    shakeInterval = setInterval(triggerShake, 60000);
    triggerShake();
    fetchMessages();
});

// Cleanup shake interval
onUnmounted(() => {
    clearInterval(shakeInterval);
});

// Watch for chat open/close and hover to disable shake animation
watch(isChatOpen, (newVal) => {
    const button = document.querySelector('.chat-button');
    if (button) {
        button.style.animationPlayState = newVal || isHovered.value ? 'paused' : 'running';
    }
});
</script>

<template>

    <Head title="Chat Test" />
    <!-- Chat Icon -->
    <div class="fixed bottom-0 right-0 z-30 mr-5 mb-5">
        <button @click="isChatOpen = !isChatOpen" @mouseenter="isHovered = true" @mouseleave="isHovered = false"
            :class="['chat-button', 'bg-gray-600', 'text-white', 'rounded-full', 'p-3', 'shadow-lg', 'hover:bg-green-400', 'focus:outline-none', { 'animate-shake': !isChatOpen && !isHovered }]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                class="bi bi-chat-dots text-blue-500" viewBox="0 0 16 16">
                <path
                    d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                <path
                    d="M2.165 15.803a10 10 0 0 0 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318" />
            </svg>
        </button>
    </div>

    <!-- Chat Box -->
    <div v-if="isChatOpen" class="fixed bottom-0 right-0 z-30 mr-5 mb-20 w-92">
        <div class="bg-white shadow-lg rounded-lg flex flex-col h-[400px]">
            <div class="bg-gray-800 text-white p-4 rounded-t-lg">
                <h5 class="text-lg font-semibold">Chat Support</h5>
            </div>

            <div ref="scrollRef" class="p-4 overflow-auto flex-1">
                <div v-for="(msg, index) in messages" :key="index" class="mb-4">
                    <div v-if="msg.sender === 'receiver'" class="flex justify-start">
                        <div class="bg-gray-200 p-3 rounded-lg max-w-[70%]">
                            <p class="text-gray-700">{{ msg.text }}</p>
                            <small class="text-gray-500">{{ msg.timestamp }}</small>
                        </div>
                    </div>

                    <div v-if="msg.sender === 'sender'" class="flex justify-end">
                        <div class="bg-blue-500 text-white p-3 rounded-lg max-w-[70%]">
                            <p>{{ msg.text }}</p>
                            <small class="text-gray-300">{{ msg.timestamp }}</small>
                        </div>
                    </div>
                </div>
                <p v-if="isTyping" class="text-gray-500 italic">User is typing...</p>
            </div>

            <div class="p-4 border-t flex gap-2">
                <input v-model="message" type="text" class="w-full p-2 border rounded-lg focus:outline-none"
                    placeholder="Type your message..." @keyup="handleTyping" @keyup.enter="sendMessage" />
                <button @click="sendMessage"
                    class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                    Send
                </button>
            </div>
        </div>
    </div>
</template>
