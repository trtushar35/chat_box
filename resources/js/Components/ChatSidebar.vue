<script setup>
import { ref } from 'vue';

const users = ref([
    { id: 1, name: 'John Doe', online: true },
    { id: 2, name: 'Jane Smith', online: false },
    { id: 3, name: 'Alice Johnson', online: true },
]);

const emit = defineEmits(['userSelected']);

const selectUser = (user) => {
    emit('userSelected', user);
};
</script>

<template>
    <div class="w-72 bg-white border-r h-full overflow-y-auto">
        <ul>
            <li v-for="user in users" :key="user.id" @click="selectUser(user)"
                class="flex items-center p-4 cursor-pointer hover:bg-gray-100 transition">
                <div class="relative w-10 h-10">
                    <img class="rounded-full object-cover w-full h-full" src="https://i.pravatar.cc/40?img=1" alt="avatar" />
                    <span v-if="user.online" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="ml-4">
                    <p class="text-gray-800 font-semibold">{{ user.name }}</p>
                    <p class="text-sm text-gray-500">{{ user.online ? 'Online' : 'Offline' }}</p>
                </div>
            </li>
        </ul>
    </div>
</template>
