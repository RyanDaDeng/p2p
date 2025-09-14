<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import P2PConfirmsPassword from '@/Components/P2PConfirmsPassword.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PTextInput from '@/Components/UI/P2PTextInput.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            双重验证
        </template>

        <template #description>
            使用双重验证为您的账户增加额外的安全保护
        </template>

        <template #content>
            <h3 v-if="twoFactorEnabled && ! confirming" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                您已启用双重验证
            </h3>

            <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                完成双重验证设置
            </h3>

            <h3 v-else class="text-lg font-medium text-gray-900 dark:text-gray-100">
                您尚未启用双重验证
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p>
                    启用双重验证后，登录时需要输入手机验证器应用生成的安全令牌。您可以使用 Google Authenticator 或其他验证器应用获取此令牌。
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p v-if="confirming" class="font-semibold">
                            要完成双重验证设置，请使用手机验证器应用扫描下方二维码，或手动输入设置密钥，然后提供生成的验证码。
                        </p>

                        <p v-else>
                            双重验证已启用。请使用手机验证器应用扫描下方二维码，或手动输入设置密钥。
                        </p>
                    </div>

                    <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />

                    <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p class="font-semibold">
                            设置密钥：<span v-html="setupKey"></span>
                        </p>
                    </div>

                    <div v-if="confirming" class="mt-4">
                        <InputLabel for="code" value="验证码" />

                        <P2PTextInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            inputmode="numeric"
                            placeholder="输入6位验证码"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication"
                            :variant="confirmationForm.errors.code ? 'error' : 'default'"
                        />

                        <InputError :message="confirmationForm.errors.code" class="mt-2" />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && ! confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p class="font-semibold">
                            请将这些恢复代码保存在安全的密码管理器中。如果您的双重验证设备丢失，可以使用这些代码恢复账户访问权限。
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-900 dark:text-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <P2PConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <P2PButton variant="primary" type="button" :loading="enabling" :disabled="enabling">
                            启用
                        </P2PButton>
                    </P2PConfirmsPassword>
                </div>

                <div v-else>
                    <P2PConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <P2PButton
                            v-if="confirming"
                            variant="primary"
                            type="button"
                            class="me-3"
                            :loading="enabling || confirmationForm.processing"
                            :disabled="enabling || confirmationForm.processing"
                        >
                            确认
                        </P2PButton>
                    </P2PConfirmsPassword>

                    <P2PConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <P2PButton
                            v-if="recoveryCodes.length > 0 && ! confirming"
                            variant="secondary"
                            class="me-3"
                        >
                            重新生成恢复代码
                        </P2PButton>
                    </P2PConfirmsPassword>

                    <P2PConfirmsPassword @confirmed="showRecoveryCodes">
                        <P2PButton
                            v-if="recoveryCodes.length === 0 && ! confirming"
                            variant="secondary"
                            class="me-3"
                        >
                            显示恢复代码
                        </P2PButton>
                    </P2PConfirmsPassword>

                    <P2PConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <P2PButton
                            v-if="confirming"
                            variant="ghost"
                            :loading="disabling"
                            :disabled="disabling"
                        >
                            取消
                        </P2PButton>
                    </P2PConfirmsPassword>

                    <P2PConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <P2PButton
                            v-if="! confirming"
                            variant="danger"
                            :loading="disabling"
                            :disabled="disabling"
                        >
                            禁用
                        </P2PButton>
                    </P2PConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>
