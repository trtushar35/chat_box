<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ChatSidebar from '@/Components/ChatSidebar.vue';
import ChatWindow from '@/Components/ChatWindow.vue';
import { ref } from 'vue';

const selectedUser = ref(null);

const handleUserSelected = (user) => {
    selectedUser.value = user;
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="flex h-[80vh] max-w-7xl mx-auto bg-white shadow overflow-hidden rounded-lg">
            <!-- Sidebar -->
            <ChatSidebar @userSelected="handleUserSelected" />

            <!-- Chat Window -->
            <div class="flex-1">
                <ChatWindow :selectedUser="selectedUser" />
            </div>
        </div>
    </AppLayout>
</template>
