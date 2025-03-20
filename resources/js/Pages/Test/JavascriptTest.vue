<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const isChatOpen = ref(false);

const toggleChat = () => {
    isChatOpen.value = !isChatOpen.value;
};
</script>

<template>
    <Head title="Chat Test" />
    <div class="min-h-screen bg-gray-100 relative">
        <!-- Chat Icon -->
        <div class="fixed bottom-0 right-0 z-30" :style="{ marginRight: '20px', marginTop: '40%' }">
            <button @click="toggleChat" class="bg-red-500 text-white rounded-full p-3 shadow-lg hover:bg-red-400 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-dots text-blue-500" viewBox="0 0 16 16">
                    <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    <path d="M2.165 15.803l.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2" />
                </svg>
            </button>
        </div>

        <!-- Chat Box -->
        <div v-if="isChatOpen" class="fixed bottom-0 right-0 m-3 z-30" :style="{ marginRight: '20px', marginTop: '27%' }">
            <div class="bg-white shadow-lg rounded-lg w-80">
                <div class="bg-gray-800 text-white p-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold">Chat Support</h5>
                </div>
                <div class="p-4 overflow-auto max-h-72">
                    <p class="text-gray-700">Hello! How can we assist you today?</p>
                </div>
                <div class="p-4 border-t">
                    <input type="text" class="w-full p-2 border rounded-lg" placeholder="Type your message..." />
                </div>
            </div>
        </div>
    </div>
</template>
