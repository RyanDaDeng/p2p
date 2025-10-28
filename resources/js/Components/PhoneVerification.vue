<template>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    手机号码验证
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    验证您的手机号码以提高账户安全性
                </p>
            </div>

            <div class="p-6">
                <div v-if="status.is_verified" class="flex items-center space-x-2 text-emerald-600 dark:text-emerald-500 mb-6">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">手机号码已验证</span>
                </div>

                <div v-if="status.phone_number && status.is_verified" class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded">
                    <div class="text-sm text-gray-600 dark:text-gray-400">已验证手机号码</div>
                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-1">
                        {{ formatPhoneNumber(status.phone_number) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        验证时间: {{ formatDate(status.verified_at) }}
                    </div>
                </div>

                <form v-if="!status.is_verified" @submit.prevent="step === 1 ? sendCode() : verifyCode()" class="space-y-6">
                    <div v-if="step === 1">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            手机号码
                        </label>
                        <div class="mt-1 relative">
                            <div
                                class="flex rounded transition-all"
                                :class="phoneInputFocused ? 'ring-2 ring-emerald-500' : ''"
                            >
                                <P2PPhoneCodeSelect
                                    v-model="selectedCountryCode"
                                    :countries="countries"
                                    :disabled="loading"
                                />
                                <input
                                    id="phone"
                                    v-model="phoneNumber"
                                    type="tel"
                                    placeholder="404157872"
                                    class="flex-1 px-3 py-2 border-t border-r border-b border-l-0 border-gray-300 dark:border-gray-600 rounded-r bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="loading"
                                    @input="validatePhoneInput"
                                    @focus="phoneInputFocused = true"
                                    @blur="phoneInputFocused = false"
                                />
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            请选择国家/地区并输入手机号码
                        </p>
                    </div>

                    <div v-else>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            验证码
                        </label>
                        <div class="mt-1">
                            <input
                                id="code"
                                v-model="verificationCode"
                                type="text"
                                placeholder="输入6位验证码"
                                maxlength="6"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                :disabled="loading"
                            />
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                验证码已发送至 {{ formatPhoneNumber(fullPhoneNumber) }}
                            </p>
                            <button
                                type="button"
                                @click="resendCode"
                                :disabled="countdown > 0 || loading"
                                class="text-xs text-emerald-600 dark:text-emerald-500 hover:text-emerald-500 dark:hover:text-emerald-400 disabled:text-gray-400 dark:disabled:text-gray-600 cursor-pointer disabled:cursor-not-allowed"
                            >
                                {{ countdown > 0 ? `重新发送 (${countdown}s)` : '重新发送' }}
                            </button>
                        </div>
                    </div>

                    <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                        <p class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
                    </div>

                    <div v-if="success" class="p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded">
                        <p class="text-sm text-emerald-600 dark:text-emerald-400">{{ success }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            v-if="step === 2"
                            type="button"
                            @click="handleBack"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                        >
                            返回
                        </button>
                        <div v-else></div>

                        <button
                            type="submit"
                            :disabled="loading || (step === 1 && !phoneNumber) || (step === 2 && verificationCode.length !== 6)"
                            class="px-6 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 disabled:bg-gray-300 dark:disabled:bg-gray-700 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer"
                        >
                            <span v-if="loading">处理中...</span>
                            <span v-else-if="step === 1">发送验证码</span>
                            <span v-else>验证</span>
                        </button>
                    </div>
                </form>

                <div v-if="status.is_verified" class="mt-6">
                    <button
                        @click="changePhoneNumber"
                        class="text-sm text-emerald-600 dark:text-emerald-500 hover:text-emerald-500 dark:hover:text-emerald-400 cursor-pointer"
                    >
                        更换手机号码
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import P2PPhoneCodeSelect from '@/Components/UI/P2PPhoneCodeSelect.vue';
import { getCountriesWithPopularFirst, formatPhoneNumber as formatPhoneUtil } from '@/utils/countries';

const step = ref(1);
const selectedCountryCode = ref('+61');
const phoneNumber = ref('');
const verificationCode = ref('');
const loading = ref(false);
const error = ref('');
const success = ref('');
const countdown = ref(0);
const status = ref({
    phone_number: null,
    is_verified: false,
    verified_at: null,
});
const countries = ref(getCountriesWithPopularFirst());
const phoneInputFocused = ref(false);

let countdownInterval = null;

const fullPhoneNumber = computed(() => {
    return selectedCountryCode.value + phoneNumber.value;
});

const fetchStatus = async () => {
    try {
        const response = await axios.get('/web/api/phone-verification/status');
        status.value = response.data;
        if (status.value.phone_number && !status.value.is_verified) {
            // 解析已保存的电话号码
            const savedNumber = status.value.phone_number;
            // 按phone_code长度降序排序，确保更长的代码先匹配
            const sortedCountries = [...countries.value].sort((a, b) =>
                b.phone_code.length - a.phone_code.length
            );
            const countryCode = sortedCountries.find(c => savedNumber.startsWith(c.phone_code));
            if (countryCode) {
                selectedCountryCode.value = countryCode.phone_code;
                phoneNumber.value = savedNumber.substring(countryCode.phone_code.length);
            } else {
                phoneNumber.value = savedNumber;
            }
            step.value = 2;
        }
    } catch (err) {
        console.error('Failed to fetch status:', err);
    }
};


const sendCode = async () => {
    loading.value = true;
    error.value = '';
    success.value = '';

    try {
        const response = await axios.post('/web/api/phone-verification/send', {
            phone_number: fullPhoneNumber.value,
        });

        if (response.data.success) {
            success.value = response.data.message;
            step.value = 2;
            startCountdown();
        } else {
            error.value = response.data.message;
        }
    } catch (err) {
        error.value = err.response?.data?.message || '发送验证码失败，请稍后再试';
    } finally {
        loading.value = false;
    }
};

const verifyCode = async () => {
    loading.value = true;
    error.value = '';
    success.value = '';

    try {
        const response = await axios.post('/web/api/phone-verification/verify', {
            code: verificationCode.value,
        });

        if (response.data.success) {
            success.value = response.data.message;
            await fetchStatus();
        } else {
            error.value = response.data.message;
        }
    } catch (err) {
        error.value = err.response?.data?.message || '验证失败，请稍后再试';
    } finally {
        loading.value = false;
    }
};

const resendCode = async () => {
    if (countdown.value > 0) return;
    await sendCode();
};

const startCountdown = () => {
    // 清除之前的计时器，避免重复
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }

    countdown.value = 60;
    countdownInterval = setInterval(() => {
        countdown.value--;
        if (countdown.value <= 0) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }, 1000);
};

const handleBack = () => {
    // 清除计时器
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
    countdown.value = 0;

    step.value = 1;
    verificationCode.value = '';
    error.value = '';
    success.value = '';
};

const changePhoneNumber = () => {
    // 清除计时器
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
    countdown.value = 0;

    status.value.is_verified = false;
    step.value = 1;
    phoneNumber.value = '';
    verificationCode.value = '';
    selectedCountryCode.value = '+61';
    error.value = '';
    success.value = '';
};

const validatePhoneInput = (event) => {
    // 只允许输入数字
    event.target.value = event.target.value.replace(/[^0-9]/g, '');
    phoneNumber.value = event.target.value;
};

const formatPhoneNumber = (phone) => {
    return formatPhoneUtil(phone);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleString('zh-CN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    });
};

onMounted(async () => {
    await fetchStatus();
});

onBeforeUnmount(() => {
    // 组件卸载时清理计时器
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
});
</script>