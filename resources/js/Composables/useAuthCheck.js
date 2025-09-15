import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

export function useAuthCheck() {
    const showLoginPrompt = ref(false);
    const loginPromptMessage = ref('');
    const returnUrl = ref('');

    const page = usePage();

    const isAuthenticated = () => {
        return !!page.props.auth?.user;
    };

    const requireAuth = (action = null, customMessage = null) => {
        if (!isAuthenticated()) {
            loginPromptMessage.value = customMessage || '请先登录您的账户以继续此操作';
            returnUrl.value = window.location.pathname + window.location.search;
            showLoginPrompt.value = true;
            return false;
        }

        if (action && typeof action === 'function') {
            action();
        }

        return true;
    };

    const navigateWithAuth = (url, customMessage = null) => {
        if (requireAuth(null, customMessage)) {
            router.visit(url);
        }
    };

    return {
        isAuthenticated,
        requireAuth,
        navigateWithAuth,
        showLoginPrompt,
        loginPromptMessage,
        returnUrl
    };
}