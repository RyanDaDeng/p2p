<script setup>
import { ref, watch } from 'vue';
import Modal from '../Modal.vue';
import P2PButton from './P2PButton.vue';
import P2PTextInput from './P2PTextInput.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '确认操作',
    },
    message: {
        type: String,
        default: '',
    },
    confirmText: {
        type: String,
        default: '确认',
    },
    cancelText: {
        type: String,
        default: '取消',
    },
    confirmVariant: {
        type: String,
        default: 'primary',
    },
    danger: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    inputMode: {
        type: Boolean,
        default: false,
    },
    inputLabel: {
        type: String,
        default: '',
    },
    inputPlaceholder: {
        type: String,
        default: '请输入...',
    },
    inputRequired: {
        type: Boolean,
        default: false,
    },
    inputType: {
        type: String,
        default: 'text',
    },
});

const emit = defineEmits(['confirm', 'cancel', 'close']);

const inputValue = ref('');

watch(() => props.show, (newVal) => {
    if (newVal) {
        inputValue.value = '';
    }
});

const handleConfirm = () => {
    if (props.inputMode) {
        if (props.inputRequired && !inputValue.value.trim()) {
            return;
        }
        emit('confirm', inputValue.value);
    } else {
        emit('confirm');
    }
};

const handleCancel = () => {
    emit('cancel');
    emit('close');
};
</script>

<template>
    <Modal :show="show" :closeable="!loading" max-width="md" @close="handleCancel">
        <div class="p-6">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full" 
                :class="danger ? 'bg-red-100 dark:bg-red-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30'">
                <svg v-if="danger" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <svg v-else class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Title -->
            <div class="mt-3 text-center">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    {{ title }}
                </h3>

                <!-- Message -->
                <div v-if="message" class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                    <p>{{ message }}</p>
                </div>

                <!-- Input Field -->
                <div v-if="inputMode" class="mt-4">
                    <label v-if="inputLabel" class="block text-sm font-medium text-slate-700 dark:text-slate-300 text-left mb-2">
                        {{ inputLabel }}
                        <span v-if="inputRequired" class="text-red-500">*</span>
                    </label>
                    <P2PTextInput
                        v-model="inputValue"
                        :type="inputType"
                        :placeholder="inputPlaceholder"
                        :required="inputRequired"
                        class="w-full"
                        @keyup.enter="handleConfirm"
                    />
                </div>

                <!-- Content Slot -->
                <div v-if="$slots.default" class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                    <slot></slot>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end space-x-3">
            <P2PButton
                variant="ghost"
                :disabled="loading"
                @click="handleCancel"
            >
                {{ cancelText }}
            </P2PButton>
            
            <P2PButton
                :variant="danger ? 'danger' : confirmVariant"
                :loading="loading"
                :disabled="loading || (inputMode && inputRequired && !inputValue.trim())"
                @click="handleConfirm"
            >
                {{ confirmText }}
            </P2PButton>
        </div>
    </Modal>
</template>