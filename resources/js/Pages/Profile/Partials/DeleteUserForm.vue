<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import InputError from '@/Components/InputError.vue';
import P2PTextInput from '@/Components/UI/P2PTextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            删除账户
        </template>

        <template #description>
            永久删除您的账户
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                一旦您的账户被删除，所有相关资源和数据将被永久删除。在删除账户之前，请下载您希望保留的任何数据或信息。
            </div>

            <div class="mt-5">
                <P2PButton variant="danger" @click="confirmUserDeletion">
                    删除账户
                </P2PButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <P2PDialog
                :show="confirmingUserDeletion"
                title="删除账户"
                icon-color="red"
                confirm-text="删除账户"
                cancel-text="取消"
                @confirm="deleteUser"
                @close="closeModal"
            >
                <div class="text-left">
                    <p class="mb-4 text-gray-700 dark:text-gray-300">您确定要删除您的账户吗？一旦删除，所有相关资源和数据将被永久删除。请输入您的密码以确认您要永久删除账户。</p>

                    <P2PTextInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="密码"
                        autocomplete="current-password"
                        @keyup.enter="deleteUser"
                        :variant="form.errors.password ? 'error' : 'default'"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
            </P2PDialog>
        </template>
    </ActionSection>
</template>
