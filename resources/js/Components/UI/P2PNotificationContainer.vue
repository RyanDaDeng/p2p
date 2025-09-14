<script setup>
import { ref, provide, onMounted } from 'vue';
import P2PNotification from './P2PNotification.vue';
import { setNotificationCallback } from '@/axios';

const notifications = ref([]);

const addNotification = (options) => {
    const id = Date.now().toString();
    const notification = {
        id,
        ...options,
    };
    notifications.value.push(notification);
    return id;
};

const removeNotification = (id) => {
    const index = notifications.value.findIndex(n => n.id === id);
    if (index > -1) {
        notifications.value.splice(index, 1);
    }
};

const notify = {
    success: (message, options = {}) => {
        return addNotification({ type: 'success', message, ...options });
    },
    error: (message, options = {}) => {
        return addNotification({ type: 'error', message, ...options });
    },
    warning: (message, options = {}) => {
        return addNotification({ type: 'warning', message, ...options });
    },
    info: (message, options = {}) => {
        return addNotification({ type: 'info', message, ...options });
    },
    remove: removeNotification,
};

provide('notify', notify);

// Register global notification callback for axios
onMounted(() => {
    setNotificationCallback((type, message) => {
        notify[type](message);
    });
});

defineExpose({ notify });
</script>

<template>
    <div>
        <!-- Slot for app content -->
        <slot />

        <!-- Notification container -->
        <div class="fixed top-0 left-1/2 transform -translate-x-1/2 z-[9999] flex flex-col items-center space-y-3 pointer-events-none p-4 w-full max-w-md">
            <TransitionGroup name="notification">
                <P2PNotification
                    v-for="notification in notifications"
                    :key="notification.id"
                    v-bind="notification"
                    @close="removeNotification"
                />
            </TransitionGroup>
        </div>
    </div>
</template>

<style scoped>
.notification-move,
.notification-enter-active,
.notification-leave-active {
    transition: all 0.3s ease;
}

.notification-enter-from {
    opacity: 0;
    transform: translateY(-30px);
}

.notification-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.notification-leave-active {
    position: absolute;
    right: 0;
}
</style>