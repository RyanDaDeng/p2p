<template>
    <P2PAppLayout>
        <Head title="成为商家" />
        
        <div class="min-h-screen bg-gray-50 dark:bg-slate-950">
            <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                <!-- 页面标题 -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-slate-100">成为商家</h1>
                    <p class="mt-2 text-gray-600 dark:text-slate-400">开始在平台上发布交易广告，赚取收益</p>
                </div>

                <!-- 商家类型选择 -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">选择商家类型</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- 非认证商家 -->
                        <label 
                            class="relative flex flex-col p-6 border-2 rounded-xl cursor-pointer transition-all"
                            :class="vendorType === 'standard' 
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' 
                                : 'border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600 bg-white dark:bg-slate-900'"
                        >
                            <input 
                                type="radio" 
                                v-model="vendorType" 
                                value="standard"
                                class="sr-only"
                            />
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-lg bg-gray-100 dark:bg-slate-800">
                                    <svg class="w-6 h-6 text-gray-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-slate-100">非认证商家</p>
                                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded">基础</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">快速开始交易，无需身份验证</p>
                                    <ul class="mt-3 space-y-1 text-sm text-gray-500 dark:text-slate-500">
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            快速审核通过
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            基础交易功能
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            无认证标识
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="vendorType === 'standard'" class="absolute top-4 right-4">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </label>

                        <!-- 认证商家 -->
                        <label 
                            class="relative flex flex-col p-6 border-2 rounded-xl cursor-pointer transition-all"
                            :class="vendorType === 'verified' 
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                : 'border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600 bg-white dark:bg-slate-900'"
                        >
                            <input 
                                type="radio" 
                                v-model="vendorType" 
                                value="verified"
                                class="sr-only"
                            />
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-slate-100">认证商家</p>
                                        <span class="px-2 py-0.5 text-xs font-medium bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded">推荐</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">获得认证标识，提高用户信任度</p>
                                    <ul class="mt-3 space-y-1 text-sm text-gray-500 dark:text-slate-500">
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            认证标识显示
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            优先广告展示
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            更高交易限额
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            低手续费率
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="vendorType === 'verified'" class="absolute top-4 right-4">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 非认证商家表单 -->
                <div v-if="vendorType === 'standard'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800">
                    <form @submit.prevent="submitApplication">
                        <!-- 基本信息 -->
                        <div class="p-6 border-b border-gray-200 dark:border-slate-800">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-4">基本信息</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- 国家选择 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                        所在国家/地区 <span class="text-red-500">*</span>
                                    </label>
                                    <P2PSelect
                                        v-model="form.country"
                                        :options="countries"
                                        placeholder="选择国家/地区"
                                        size="md"
                                        class="w-full"
                                    />
                                </div>
                                
                                <!-- 手机号码 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                        手机号码 <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-2">
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            placeholder="+86 138****8888"
                                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            :disabled="phoneVerified"
                                        />
                                        <P2PButton 
                                            v-if="!phoneVerified"
                                            @click="sendVerificationCode"
                                            variant="outline"
                                            size="md"
                                            :disabled="verificationCooldown > 0"
                                        >
                                            {{ verificationCooldown > 0 ? `${verificationCooldown}s` : '发送验证码' }}
                                        </P2PButton>
                                        <div v-else class="flex items-center gap-2 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-emerald-700 dark:text-emerald-400">已验证</span>
                                        </div>
                                    </div>
                                    
                                    <!-- 验证码输入 -->
                                    <div v-if="showVerificationInput && !phoneVerified" class="mt-2 flex gap-2">
                                        <input
                                            v-model="verificationCode"
                                            type="text"
                                            placeholder="输入6位验证码"
                                            maxlength="6"
                                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        />
                                        <P2PButton @click="verifyPhone" variant="primary" size="sm">
                                            验证
                                        </P2PButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <!-- 提交按钮 -->
                        <div class="p-6 bg-gray-50 dark:bg-slate-800/50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500 dark:text-slate-400">
                                    <p>* 必填项</p>
                                    <p class="mt-1">提交后我们将在1-3个工作日内审核您的申请</p>
                                </div>
                                <P2PButton 
                                    type="submit"
                                    variant="primary"
                                    size="lg"
                                    :disabled="!canSubmit"
                                >
                                    提交申请
                                </P2PButton>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 认证商家表单（暂时不做） -->
                <div v-if="vendorType === 'verified'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800 p-8">
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full mb-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100 mb-2">认证商家申请</h3>
                        <p class="text-gray-600 dark:text-slate-400 mb-6">认证商家需要完成身份验证，该功能正在开发中</p>
                        <P2PButton variant="outline" @click="vendorType = 'standard'">
                            选择非认证商家
                        </P2PButton>
                    </div>
                </div>
            </div>
        </div>

        <!-- KYC认证弹窗 -->
        <KYCModal v-model="showKYCModal" @complete="handleKYCComplete" />
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PSelect from '@/Components/UI/P2PSelect.vue';
import KYCModal from '@/Components/Vendor/KYCModal.vue';

// 状态
const vendorType = ref('');
const phoneVerified = ref(false);
const showKYCModal = ref(false);
const showVerificationInput = ref(false);
const verificationCode = ref('');
const verificationCooldown = ref(0);

// 表单数据
const form = ref({
    country: '',
    phone: ''
});

// 选项数据
const countries = [
    { value: 'cn', label: '中国', flag: '🇨🇳' },
    { value: 'us', label: '美国', flag: '🇺🇸' },
    { value: 'uk', label: '英国', flag: '🇬🇧' },
    { value: 'sg', label: '新加坡', flag: '🇸🇬' },
    { value: 'hk', label: '香港', flag: '🇭🇰' },
    { value: 'jp', label: '日本', flag: '🇯🇵' },
    { value: 'kr', label: '韩国', flag: '🇰🇷' },
];

// 计算属性
const canSubmit = computed(() => {
    return vendorType.value === 'standard' && form.value.country && form.value.phone && phoneVerified.value;
});

// 方法
const sendVerificationCode = () => {
    // 模拟发送验证码
    showVerificationInput.value = true;
    verificationCooldown.value = 60;
    
    const timer = setInterval(() => {
        verificationCooldown.value--;
        if (verificationCooldown.value <= 0) {
            clearInterval(timer);
        }
    }, 1000);
};

const verifyPhone = () => {
    // 模拟验证
    if (verificationCode.value === '123456') {
        phoneVerified.value = true;
        showVerificationInput.value = false;
    }
};

const submitApplication = () => {
    // 提交申请
    console.log('提交申请', form.value);
};

const handleKYCComplete = () => {
    showKYCModal.value = false;
};
</script>