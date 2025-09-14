<script setup>
import { ref } from 'vue';
import Modal from '../Modal.vue';
import P2PButton from '../UI/P2PButton.vue';
import P2PToggle from '../UI/P2PToggle.vue';
import P2PTextInput from '../UI/P2PTextInput.vue';
import InputLabel from '../InputLabel.vue';
import InputError from '../InputError.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'save']);

// Simplified Notification Settings
const settings = ref({
    // Push Notifications
    push: {
        enabled: true,
    },
    
    // Email Notifications
    email: {
        enabled: true,
        address: '',              // 通知邮箱
    },
});

const errors = ref({
    email: '',
});

const validateEmail = () => {
    if (settings.value.email.enabled && settings.value.email.address) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(settings.value.email.address)) {
            errors.value.email = '请输入有效的邮箱地址';
            return false;
        }
    }
    errors.value.email = '';
    return true;
};

const saveSettings = () => {
    if (!validateEmail()) {
        return;
    }
    
    emit('save', settings.value);
    emit('close');
};

</script>

<template>
    <Modal :show="show" max-width="lg" @close="$emit('close')">
        <div class="p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                通知设置
            </h2>

            <!-- Online Status Notice -->
            <div class="mb-4 sm:mb-6 p-2.5 sm:p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-100">
                        只有在 <span class="font-semibold">在线状态</span> 时才会接收推送通知，<span class="font-semibold">争议事件</span> 除外
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Push Notifications -->
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1.5 sm:p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    手机推送通知
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    接收实时交易提醒
                                </p>
                            </div>
                        </div>
                        <P2PToggle v-model="settings.push.enabled" size="sm" />
                    </div>

                </div>

                <!-- Email Notifications -->
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1.5 sm:p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                                    邮箱通知
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    接收交易邮件提醒
                                </p>
                            </div>
                        </div>
                        <P2PToggle v-model="settings.email.enabled" size="sm" />
                    </div>

                    <div v-if="settings.email.enabled" class="space-y-3">
                        <div>
                            <InputLabel value="通知邮箱" class="text-sm" />
                            <P2PTextInput
                                v-model="settings.email.address"
                                type="email"
                                placeholder="输入邮箱地址"
                                size="sm"
                                :variant="errors.email ? 'error' : 'default'"
                                @blur="validateEmail"
                            />
                            <InputError :message="errors.email" class="mt-1" />
                        </div>

                    </div>
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-2 sm:gap-3">
                <P2PButton @click="$emit('close')" variant="ghost" fullWidth class="sm:w-auto">
                    取消
                </P2PButton>
                <P2PButton @click="saveSettings" variant="primary" fullWidth class="sm:w-auto">
                    保存设置
                </P2PButton>
            </div>
        </div>
    </Modal>
</template>