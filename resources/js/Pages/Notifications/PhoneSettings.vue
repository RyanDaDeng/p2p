<template>
    <P2PAppLayout>
        <Head title="手机通知设置" />

        <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="py-6">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">手机通知设置</h1>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5">管理您的短信通知偏好</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Phone Verification Check -->
                <div v-if="!$page.props.auth.user?.phone_verified_at" class="mb-8">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200 mb-2">
                                    需要先验证手机号码
                                </h3>
                                <p class="text-amber-700 dark:text-amber-300 mb-4">
                                    您需要先验证手机号码才能接收短信通知。验证后您将能够接收订单状态更新、交易消息和安全提醒。
                                </p>
                                <Link
                                    href="/user/profile#tab-phone"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 text-white rounded font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500/20 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    去验证手机号码
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings (Only show if phone is verified) -->
                <div v-if="$page.props.auth.user?.phone_verified_at">
                    <!-- Phone Settings Card -->
                    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">短信通知</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">通过短信接收重要的交易通知</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">

                            <!-- Phone Number Display -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">已验证手机号码</p>
                                        <p class="text-lg font-mono text-gray-900 dark:text-gray-100 mt-1">
                                            {{ formatPhoneNumber($page.props.auth.user.phone_number) }}
                                        </p>
                                    </div>
                                    <Link
                                        href="/user/profile#tab-phone"
                                        class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 dark:hover:text-emerald-300 cursor-pointer"
                                    >
                                        更换号码
                                    </Link>
                                </div>
                            </div>

                            <!-- Connection Status -->
                            <div v-if="notificationsEnabled" class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">短信通知已开启</p>
                                        <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1">您将接收所有重要通知</p>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">短信通知已关闭</p>
                                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">您不会收到任何短信通知</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Toggle Notification -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">启用短信通知</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">接收订单状态、安全提醒等重要通知</p>
                                </div>
                                <P2PToggle
                                    v-model="notificationsEnabled"
                                    @change="toggleNotifications"
                                    :loading="toggling"
                                    :disabled="toggling"
                                />
                            </div>

                            <!-- Notification Info -->
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded">
                                <div class="flex gap-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">通知类型：</span>订单状态更新、新消息提醒、安全警告等重要通知将通过短信发送到您的手机
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Notification -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">测试通知</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">发送测试消息到您的手机</p>
                                </div>
                                <P2PButton @click="sendTestSMS" variant="outline" size="sm" :loading="sendingTest" :disabled="!notificationsEnabled || sendingTest">
                                    <template #icon>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </template>
                                    发送测试
                                </P2PButton>
                            </div>

                            <!-- Success Message -->
                            <div v-if="testSuccess" class="p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded">
                                <div class="flex gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-xs text-emerald-700 dark:text-emerald-300">
                                        测试短信已发送，请查看您的手机
                                    </p>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div v-if="testError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                                <div class="flex gap-2">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-xs text-red-700 dark:text-red-300">
                                        {{ testError }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PToggle from '@/Components/UI/P2PToggle.vue';
import axios from 'axios';
import { formatPhoneNumber as formatPhoneUtil } from '@/utils/countries';

const notificationsEnabled = ref(false);
const toggling = ref(false);
const sendingTest = ref(false);
const testSuccess = ref(false);
const testError = ref('');

const formatPhoneNumber = (phone) => {
    return formatPhoneUtil(phone);
};

const toggleNotifications = async (value) => {
    toggling.value = true;

    try {
        await axios.post('/web/api/notifications/phone-settings/toggle', {
            enabled: value
        });

        notificationsEnabled.value = value;
    } catch (error) {
        console.error('Failed to toggle notifications:', error);
        notificationsEnabled.value = !value;
    } finally {
        toggling.value = false;
    }
};

const sendTestSMS = async () => {
    sendingTest.value = true;
    testSuccess.value = false;
    testError.value = '';

    try {
        await axios.post('/web/api/notifications/test-sms');
        testSuccess.value = true;
        setTimeout(() => {
            testSuccess.value = false;
        }, 5000);
    } catch (error) {
        testError.value = error.response?.data?.message || '发送测试短信失败，请稍后再试';
        setTimeout(() => {
            testError.value = '';
        }, 5000);
    } finally {
        sendingTest.value = false;
    }
};

// Load current status
const loadStatus = async () => {
    try {
        const response = await axios.get('/web/api/notifications/phone-settings/status');
        notificationsEnabled.value = response.data.enabled || false;
    } catch (error) {
        console.error('Failed to load status:', error);
    }
};

onMounted(() => {
    loadStatus();
});
</script>