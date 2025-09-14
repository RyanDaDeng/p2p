import { inject } from 'vue';

export function useNotification() {
    const notify = inject('notify', null);
    
    if (!notify) {
        console.warn('Notification system not initialized. Make sure P2PNotificationContainer is mounted.');
        return {
            success: () => {},
            error: () => {},
            warning: () => {},
            info: () => {},
            remove: () => {},
        };
    }
    
    return notify;
}