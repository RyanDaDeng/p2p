<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="$emit('close')">
        <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-lg shadow-xl">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-slate-100">KYC身份认证</h2>
                <button 
                    @click="$emit('close')"
                    class="p-2 -mr-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- KYC Benefits -->
                <div class="p-4 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">KYC认证权益</h3>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• 获得认证商家标识，增加信任度</li>
                        <li>• 提高交易限额</li>
                        <li>• 优先展示您的广告</li>
                        <li>• 降低手续费率</li>
                    </ul>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitKYC" class="space-y-4">
                    <!-- Real Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">真实姓名</label>
                        <input 
                            v-model="form.realName"
                            type="text" 
                            required
                            placeholder="请输入您的真实姓名"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100"
                        >
                    </div>

                    <!-- ID Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">证件类型</label>
                        <select 
                            v-model="form.idType" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100"
                        >
                            <option value="">请选择证件类型</option>
                            <option value="id_card">身份证</option>
                            <option value="passport">护照</option>
                            <option value="driver_license">驾驶证</option>
                        </select>
                    </div>

                    <!-- ID Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">证件号码</label>
                        <input 
                            v-model="form.idNumber"
                            type="text" 
                            required
                            placeholder="请输入证件号码"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100"
                        >
                    </div>

                    <!-- ID Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">证件照片</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Front Photo -->
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">正面</label>
                                <div class="relative border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-4 text-center hover:border-blue-400 dark:hover:border-blue-400 transition-colors">
                                    <input 
                                        type="file" 
                                        accept="image/*"
                                        @change="handleFileUpload($event, 'front')"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    >
                                    <div v-if="!form.frontPhoto" class="text-gray-400 dark:text-slate-500">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <p class="text-xs">上传正面</p>
                                    </div>
                                    <div v-else class="text-green-600 dark:text-green-400">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <p class="text-xs">已上传</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Back Photo -->
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">背面</label>
                                <div class="relative border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-4 text-center hover:border-blue-400 dark:hover:border-blue-400 transition-colors">
                                    <input 
                                        type="file" 
                                        accept="image/*"
                                        @change="handleFileUpload($event, 'back')"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    >
                                    <div v-if="!form.backPhoto" class="text-gray-400 dark:text-slate-500">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <p class="text-xs">上传背面</p>
                                    </div>
                                    <div v-else class="text-green-600 dark:text-green-400">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <p class="text-xs">已上传</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">请确保照片清晰，信息完整可见</p>
                    </div>

                    <!-- Selfie -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">手持证件自拍</label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-400 transition-colors">
                            <input 
                                type="file" 
                                accept="image/*"
                                @change="handleFileUpload($event, 'selfie')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            <div v-if="!form.selfiePhoto" class="text-gray-400 dark:text-slate-500">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="text-sm">上传手持证件自拍照</p>
                                <p class="text-xs mt-1">请手持证件与面部一起拍照</p>
                            </div>
                            <div v-else class="text-green-600 dark:text-green-400">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <p class="text-sm">自拍照已上传</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="flex gap-3 p-6 border-t border-gray-200 dark:border-slate-700">
                <button 
                    @click="$emit('close')"
                    type="button"
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors"
                >
                    取消
                </button>
                <button 
                    @click="submitKYC"
                    :disabled="!canSubmit || submitting"
                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg transition-colors"
                >
                    {{ submitting ? '提交中...' : '提交认证' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

defineEmits(['close', 'submit']);

const form = ref({
    realName: '',
    idType: '',
    idNumber: '',
    frontPhoto: null,
    backPhoto: null,
    selfiePhoto: null
});

const submitting = ref(false);

const canSubmit = computed(() => {
    return form.value.realName && 
           form.value.idType && 
           form.value.idNumber && 
           form.value.frontPhoto && 
           form.value.backPhoto && 
           form.value.selfiePhoto;
});

const handleFileUpload = (event, type) => {
    const file = event.target.files[0];
    if (file) {
        form.value[`${type}Photo`] = file;
    }
};

const submitKYC = async () => {
    if (!canSubmit.value || submitting.value) return;

    submitting.value = true;
    
    // Mock submission delay
    setTimeout(() => {
        submitting.value = false;
        alert('KYC认证材料已提交，我们将在1-3个工作日内完成审核');
        form.value = {
            realName: '',
            idType: '',
            idNumber: '',
            frontPhoto: null,
            backPhoto: null,
            selfiePhoto: null
        };
        // Emit submit event with form data
        // $emit('submit', form.value);
        // Close modal
        // $emit('close');
    }, 2000);
};
</script>