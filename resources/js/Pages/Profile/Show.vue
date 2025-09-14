<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const activeTab = ref('tab-profile');

const tabs = [
    { id: 'tab-profile', name: '基本信息', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
    { id: 'tab-password', name: '修改密码', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
    { id: 'tab-2fa', name: '双重验证', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' },
    { id: 'tab-kyc', name: '实名认证', icon: 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2' },
    { id: 'tab-devices', name: '登录设备', icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
];

// 处理 hash 变化
const handleHashChange = (e) => {
    if (e) {
        e.preventDefault();
    }
    const hash = window.location.hash.slice(1);
    const validTabs = tabs.map(tab => tab.id);
    if (hash && validTabs.includes(hash)) {
        activeTab.value = hash;
        // 不需要滚动，让页面保持当前位置
    }
};

// 监听 hash 变化
onMounted(() => {
    handleHashChange();
    window.addEventListener('hashchange', handleHashChange);
    // 初始加载时滚动到顶部
    if (window.location.hash) {
        window.scrollTo({ top: 0, behavior: 'instant' });
    }
});

// 当 tab 切换时更新 URL hash
watch(activeTab, (newTab) => {
    // 使用 replaceState 避免添加历史记录
    history.replaceState(null, null, `#${newTab}`);
});

const verificationStatus = ref('unverified'); // unverified, pending, verified
</script>

<template>
    <Head title="资料设置" />
    
    <P2PAppLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-slate-950">
            <!-- Settings Header -->
            <div class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="py-6">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-slate-100">资料设置</h1>
                            <p class="text-gray-600 dark:text-slate-400 text-sm mt-0.5">管理您的账户信息和安全设置</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 sticky top-16 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex overflow-x-auto no-scrollbar">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            @click="activeTab = tab.id"
                            :class="[
                                'flex items-center gap-2 px-4 py-3 border-b-2 font-medium text-sm whitespace-nowrap transition-all cursor-pointer',
                                activeTab === tab.id 
                                    ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' 
                                    : 'border-transparent text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-200 hover:border-gray-300 dark:hover:border-slate-600'
                            ]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                            </svg>
                            {{ tab.name }}
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Profile Tab -->
                <div v-if="activeTab === 'tab-profile'" class="space-y-8">
                    <div v-if="$page.props.jetstream?.canUpdateProfileInformation">
                        <UpdateProfileInformationForm :user="$page.props.auth?.user || {}" />
                    </div>
                </div>
                
                <!-- Password Tab -->
                <div v-else-if="activeTab === 'tab-password'" class="space-y-8">
                    <div v-if="$page.props.jetstream?.canUpdatePassword">
                        <UpdatePasswordForm />
                    </div>
                </div>
                
                <!-- 2FA Tab -->
                <div v-else-if="activeTab === 'tab-2fa'" class="space-y-8">
                    <div v-if="$page.props.jetstream?.canManageTwoFactorAuthentication">
                        <TwoFactorAuthenticationForm 
                            :requires-confirmation="confirmsTwoFactorAuthentication || false"
                        />
                    </div>
                </div>
                
                <!-- KYC Tab -->
                <div v-else-if="activeTab === 'tab-kyc'" class="space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-200 dark:border-slate-800">
                        <div class="p-6 border-b border-gray-200 dark:border-slate-800">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100">实名认证</h3>
                            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">完成实名认证以解锁更多功能</p>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-8">
                                <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-slate-100 mb-2">您还未完成实名认证</h4>
                                <p class="text-sm text-gray-600 dark:text-slate-400 mb-6">完成实名认证后，您将获得更高的交易限额和信任度</p>
                                <P2PButton variant="primary" size="md">
                                    <template #icon>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </template>
                                    开始认证
                                </P2PButton>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Devices Tab -->
                <div v-else-if="activeTab === 'tab-devices'" class="space-y-8">
                    <LogoutOtherBrowserSessionsForm :sessions="sessions || []" />
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>