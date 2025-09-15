<template>
    <P2PAppLayout>
        <Head title="通知设置" />
        
        <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="py-6">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">通知设置</h1>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5">管理您的通知偏好</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Telegram Settings -->
                <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Telegram 通知</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">通过 Telegram 机器人接收实时通知</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Connection Status -->
                        <div v-if="!telegramConnected" class="space-y-4">
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">尚未连接 Telegram</p>
                                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">请按照以下步骤连接您的 Telegram 账号</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Notice -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">隐私说明</p>
                                        <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            <li class="flex items-start gap-1">
                                                <span class="text-emerald-600 dark:text-emerald-400 mt-0.5">✓</span>
                                                <span>机器人仅用于向您发送交易通知，不会获取您的任何 Telegram 权限</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-emerald-600 dark:text-emerald-400 mt-0.5">✓</span>
                                                <span>验证码仅用于建立机器人与您账号的关联，确保通知发送到正确的用户</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-emerald-600 dark:text-emerald-400 mt-0.5">✓</span>
                                                <span>我们不会读取您的聊天记录、联系人或任何个人信息</span>
                                            </li>
                                            <li class="flex items-start gap-1">
                                                <span class="text-emerald-600 dark:text-emerald-400 mt-0.5">✓</span>
                                                <span>您可以随时在 Telegram 中屏蔽机器人或在此页面解除绑定</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Connection Steps -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">连接步骤：</h4>
                                
                                <!-- Step 1: Open Telegram Bot -->
                                <div class="space-y-2">
                                    <div class="flex gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">1.</span>
                                        <span>打开 Telegram 机器人</span>
                                    </div>
                                    <a :href="telegramBotLink" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium text-sm transition-colors">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                        </svg>
                                        打开 Telegram 机器人
                                    </a>
                                </div>
                                
                                <!-- Step 2: Get Verification Code -->
                                <div class="space-y-2">
                                    <div class="flex gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">2.</span>
                                        <span>获取验证码并发送给机器人</span>
                                    </div>
                                    
                                    <!-- Get Verification Code Button -->
                                    <div v-if="!verificationCode">
                                        <P2PButton @click="getVerificationCode" :loading="loadingVerificationCode" variant="secondary" size="sm">
                                            获取验证码
                                        </P2PButton>
                                    </div>
                                    
                                    <!-- Verification Code Display -->
                                    <div v-else class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="text-xs text-blue-700 dark:text-blue-300 mb-2">请将此验证码发送给机器人</p>
                                                <div class="flex items-center gap-3">
                                                    <p class="text-2xl font-mono font-bold text-blue-900 dark:text-blue-100">{{ verificationCode }}</p>
                                                    <P2PButton @click="copyVerificationCode" variant="ghost" size="xs">
                                                        <template #icon>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                        </template>
                                                        复制
                                                    </P2PButton>
                                                </div>
                                            </div>
                                            <div class="text-xs text-blue-600 dark:text-blue-400 text-right">
                                                有效期<br/>1 小时
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Step 3: Bind Telegram -->
                                <div class="space-y-2">
                                    <div class="flex gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">3.</span>
                                        <span>点击绑定按钮完成连接</span>
                                    </div>
                                    <P2PButton @click="bindTelegram" variant="primary" size="md" :loading="bindingTelegram">
                                        <template #icon>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </template>
                                        绑定 Telegram
                                    </P2PButton>
                                </div>
                            </div>
                        </div>

                        <!-- Connected Status -->
                        <div v-else class="space-y-4">
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Telegram 已连接</p>
                                        <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1">您将接收所有重要通知</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Reminder -->
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded">
                                <div class="flex gap-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">隐私保护：</span>机器人仅向您发送通知，不会获取您的 Telegram 权限或读取任何聊天内容
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notification Settings Tip -->
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded">
                                <div class="flex gap-3">
                                    <span class="text-amber-600 dark:text-amber-400 text-lg">💡</span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200 mb-2">小贴士</p>
                                        <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
                                            为了更好的收到消息提醒，您可以在 Telegram 中进入 
                                            <strong>设置 → 通知和声音 → 私人对话</strong>，
                                            将 <strong>P2PCryptoCoinBot</strong> 添加为例外，
                                            这样来自机器人的消息您都将收到提示。
                                        </p>
                                        <div class="mt-3">
                                            <a :href="telegramBotLink" target="_blank" 
                                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium transition-colors">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56c-.21 2.27-1.13 7.75-1.6 10.29-.2 1.08-.59 1.44-.97 1.47-.82.07-1.45-.54-2.24-.99-1.24-.71-1.94-1.16-3.14-1.85-1.39-.8-.49-1.24.3-1.96.21-.19 3.85-3.52 3.91-3.82.01-.04.01-.19-.07-.27-.08-.08-.2-.05-.28-.03-.12.03-2.02.13-5.7 3.75-.54.54-1.03.8-1.48.78-.49-.02-1.42-.27-2.11-.5-.85-.28-1.53-.42-1.47-.89.03-.25.37-.51 1.02-.77 4-1.74 6.67-2.89 8.01-3.44 3.81-1.57 4.61-1.84 5.13-1.85.11 0 .37.03.53.18.14.12.18.28.2.45-.01.06-.01.24-.02.38z"/>
                                                </svg>
                                                打开机器人对话
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Notification -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">测试通知</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">发送测试消息到您的 Telegram</p>
                                </div>
                                <P2PButton @click="sendTestMessage" variant="outline" size="sm" :loading="sendingTest">
                                    <template #icon>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </template>
                                    发送测试
                                </P2PButton>
                            </div>

                            <!-- Disconnect -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                                <P2PButton @click="disconnectTelegram" variant="danger" size="sm">
                                    断开连接
                                </P2PButton>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        
        <!-- Confirmation Dialog -->
        <P2PDialog
            :show="showConfirmDialog"
            :title="confirmDialogTitle"
            :icon-color="confirmDialogDanger ? 'red' : 'emerald'"
            :confirm-text="confirmDialogIsSuccess ? '完成' : (confirmDialogCallback ? '确认' : '知道了')"
            :cancel-text="confirmDialogIsSuccess ? '' : (confirmDialogCallback ? '取消' : '')"
            @confirm="handleConfirmDialog"
            @close="handleCancelDialog"
        >
            <div v-html="confirmDialogMessage"></div>
        </P2PDialog>
    </P2PAppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import MessageDialog from '@/Components/MessageDialog';
import axios from 'axios';

const page = usePage();
const telegramBotLink = 'https://t.me/P2PCryptoCoinBot';

// Use computed to check Telegram connection from page props first
const telegramConnected = computed(() => {
    return !!page.props.auth.user?.telegram_chat_id || savedChatId.value;
});

const savedChatId = ref(page.props.auth.user?.telegram_chat_id || '');
const bindingTelegram = ref(false);
const sendingTest = ref(false);
const verificationCode = ref('');
const loadingVerificationCode = ref(false);


// Confirmation dialog state
const showConfirmDialog = ref(false);
const confirmDialogTitle = ref('');
const confirmDialogMessage = ref('');
const confirmDialogCallback = ref(null);
const confirmDialogDanger = ref(false);
const confirmDialogIsSuccess = ref(false);
const pendingTelegramData = ref(null);


onMounted(() => {
    // Don't load verification code automatically anymore
    // User will click button to get it
});

const getVerificationCode = async () => {
    loadingVerificationCode.value = true;
    try {
        const response = await axios.get('/web/api/telegram/settings');
        if (response.data.data.verification_code) {
            verificationCode.value = response.data.data.verification_code;
        }
    } catch (error) {
        console.error('Failed to load verification code:', error);
        // Check if already connected
        if (error.response?.status === 400) {
            MessageDialog.warning('Telegram 已连接');
        } else {
            MessageDialog.error('获取验证码失败，请稍后重试');
        }
    } finally {
        loadingVerificationCode.value = false;
    }
};

const bindTelegram = async () => {
    bindingTelegram.value = true;
    try {
        const response = await axios.get('/web/api/telegram/updates');
        const data = response.data.data || response.data;
        
        if (data.auto_detected && data.connected) {
            // 验证码匹配成功，已自动绑定，显示成功对话框
            const telegramUser = data.telegram_user;
            pendingTelegramData.value = data;
            
            confirmDialogTitle.value = '成功绑定 Telegram 账号';
            
            let message = '<div class="space-y-2">';
            message += '<p class="text-emerald-600 dark:text-emerald-400 font-medium">✅ 绑定成功！</p>';

            if (telegramUser) {
                message += '<div class="bg-gray-50 dark:bg-gray-800 rounded p-3 space-y-1">';
                message += `<p class="text-gray-900 dark:text-gray-100"><span class="font-medium">Telegram 用户:</span> ${telegramUser.display_name}</p>`;
                if (telegramUser.first_name || telegramUser.last_name) {
                    message += `<p class="text-gray-900 dark:text-gray-100"><span class="font-medium">昵称:</span> ${telegramUser.first_name || ''} ${telegramUser.last_name || ''}</p>`;
                }
                message += '</div>';
            }

            message += '<p class="text-sm text-gray-600 dark:text-gray-400 mt-3">您的 Telegram 已成功连接到平台账号</p>';
            message += '</div>';
            
            confirmDialogMessage.value = message;
            confirmDialogDanger.value = false;
            confirmDialogIsSuccess.value = true;  // 标记为成功状态
            confirmDialogCallback.value = () => {
                // 点击完成后刷新页面
                window.location.reload();
            };
            showConfirmDialog.value = true;
        } else {
            MessageDialog.warning('未找到匹配的验证码，请先向机器人发送验证码后重试');
        }
    } catch (error) {
        // Check if it's a duplicate binding error
        if (error.response?.status === 400 && error.response?.data?.message?.includes('已被其他用户绑定')) {
            MessageDialog.error('此 Telegram 账号已被其他用户绑定，请使用其他 Telegram 账号或联系原用户解除绑定');
        } else {
            MessageDialog.error('绑定失败，请稍后重试');
        }
    } finally {
        bindingTelegram.value = false;
    }
};


const sendTestMessage = async () => {
    sendingTest.value = true;
    try {
        await axios.post('/web/api/telegram/test');
        MessageDialog.success('测试消息已发送，请查看您的 Telegram');
    } catch (error) {
        MessageDialog.error('发送失败，请稍后重试');
    } finally {
        sendingTest.value = false;
    }
};


const disconnectTelegram = async () => {
    confirmDialogTitle.value = '断开 Telegram 连接';
    confirmDialogMessage.value = '<p class="text-gray-700 dark:text-gray-300">确定要断开 Telegram 连接吗？断开后您将无法接收平台通知。</p>';
    confirmDialogDanger.value = true;
    confirmDialogCallback.value = async () => {
        try {
            await axios.post('/web/api/telegram/disconnect');
            savedChatId.value = '';
            // Clear from page props as well
            if (page.props.auth.user) {
                page.props.auth.user.telegram_chat_id = null;
            }
            MessageDialog.success('已断开连接');
        } catch (error) {
            MessageDialog.error('操作失败，请稍后重试');
        }
    };
    showConfirmDialog.value = true;
};


const copyVerificationCode = () => {
    const textArea = document.createElement("textarea");
    textArea.value = verificationCode.value;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    MessageDialog.success(`验证码 ${verificationCode.value} 已复制到剪贴板`);
};

// Dialog handlers
const handleConfirmDialog = () => {
    if (confirmDialogCallback.value) {
        confirmDialogCallback.value();
    }
    showConfirmDialog.value = false;
    confirmDialogCallback.value = null;
    confirmDialogIsSuccess.value = false;
};

const handleCancelDialog = () => {
    showConfirmDialog.value = false;
    confirmDialogCallback.value = null;
    confirmDialogIsSuccess.value = false;
    pendingTelegramData.value = null;
};
</script>