<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const isChatOpen = ref(false);
const message = ref('');
const messages = ref([{ text: 'Hello! How can we assist you today?', sender: 'receiver' }]);
const isHovered = ref(false);
let shakeInterval = null;

const toggleChat = () => {
    isChatOpen.value = !isChatOpen.value;
};

// Function to trigger the shake effect
const triggerShake = () => {
    if (!isChatOpen.value && !isHovered.value) {
        const button = document.querySelector('.chat-button');
        if (button) {
            button.style.animationPlayState = 'running'; // Start animation
            setTimeout(() => {
                button.style.animationPlayState = 'paused'; // Pause after 2s
            }, 2000);
        }
    }
};

// Start the shake animation every 60 seconds
onMounted(() => {
    shakeInterval = setInterval(triggerShake, 60000); 
    triggerShake();
});

// Cleanup interval on component unmount
onUnmounted(() => {
    clearInterval(shakeInterval);
});

// Watch for chat open/close and hover to disable animation
watch(isChatOpen, (newVal) => {
    const button = document.querySelector('.chat-button');
    if (button) {
        if (newVal || isHovered.value) {
            button.style.animationPlayState = 'paused';
        } else {
            button.style.animationPlayState = 'running';
        }
    }
});

const sendMessage = () => {
    if (message.value.trim()) {
        messages.value.push({ text: message.value, sender: 'sender' });
        message.value = '';
    }
};
</script>

<template>
    <Head title="Chat Test" />
    <div class="min-h-screen bg-gray-100 relative">
        <!-- Chat Icon -->
        <div class="fixed bottom-0 right-0 z-30 mr-5 mb-5">
            <button @click="toggleChat" @mouseenter="isHovered = true" @mouseleave="isHovered = false"
                :class="['chat-button', 'bg-gray-600', 'text-white', 'rounded-full', 'p-3', 'shadow-lg', 'hover:bg-green-400', 'focus:outline-none', { 'animate-shake': !isChatOpen && !isHovered }]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-chat-dots text-blue-500" viewBox="0 0 16 16">
                    <path
                        d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    <path
                        d="M2.165 15.803l.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2" />
                </svg>
            </button>
        </div>

        <!-- Chat Box -->
        <div v-if="isChatOpen" class="fixed bottom-0 right-0 z-30 mr-5 mb-20 w-92">
            <div class="bg-white shadow-lg rounded-lg flex flex-col h-[400px]">
                <!-- Chat Header -->
                <div class="bg-gray-800 text-white p-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold">Chat Support</h5>
                </div>

                <!-- Chat Messages -->
                <div class="p-4 overflow-auto flex-1">
                    <div v-for="(msg, index) in messages" :key="index" class="mb-4">
                        <!-- Receiver Message -->
                        <div v-if="msg.sender === 'receiver'" class="flex justify-start">
                            <div class="bg-gray-200 p-3 rounded-lg max-w-[70%]">
                                <p class="text-gray-700">{{ msg.text }}</p>
                            </div>
                        </div>

                        <!-- Sender Message -->
                        <div v-if="msg.sender === 'sender'" class="flex justify-end">
                            <div class="bg-blue-500 text-white p-3 rounded-lg max-w-[70%]">
                                <p>{{ msg.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="p-4 border-t flex gap-2">
                    <input v-model="message" type="text" class="w-full p-2 border rounded-lg focus:outline-none"
                        placeholder="Type your message..." @keyup.enter="sendMessage" />
                    <button @click="sendMessage"
                        class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes shake {
    0%, 100% { transform: translateY(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateY(5px); }
    20%, 40%, 60%, 80% { transform: translateY(-5px); }
}

.animate-shake {
    animation: shake 2s ease-in-out;
    animation-play-state: paused; /* Start in paused state */
}

.chat-button:hover, .chat-button:focus {
    animation: none; /* Disable animation on hover or focus */
}
</style>
