<script setup>
import { ref, reactive, nextTick } from 'vue';
import P2PDialog from './UI/P2PDialog.vue';
import InputError from './InputError.vue';
import P2PTextInput from './UI/P2PTextInput.vue';

const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: '确认密码',
    },
    content: {
        type: String,
        default: '为了您的安全，请确认您的密码以继续。',
    },
    button: {
        type: String,
        default: '确认',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value?.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('confirmed'));

    }).catch(error => {
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        passwordInput.value?.focus();
    });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <P2PDialog
            :show="confirmingPassword"
            :title="title"
            icon-color="amber"
            :confirm-text="button"
            cancel-text="取消"
            @confirm="confirmPassword"
            @close="closeModal"
        >
            <div class="text-left">
                <p class="mb-4 text-gray-700 dark:text-gray-300">{{ content }}</p>

                <P2PTextInput
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    placeholder="密码"
                    autocomplete="current-password"
                    @keyup.enter="confirmPassword"
                    :variant="form.error ? 'error' : 'default'"
                />

                <InputError :message="form.error" class="mt-2" />
            </div>
        </P2PDialog>
    </span>
</template>