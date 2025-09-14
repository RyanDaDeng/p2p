<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PTextInput from '@/Components/UI/P2PTextInput.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                // 不自动聚焦，避免页面跳动
                // passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                // 不自动聚焦，避免页面跳动
                // currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <FormSection @submitted="updatePassword">
        <template #title>
            修改密码
        </template>

        <template #description>
            确保您的账户使用强密码以保证安全
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="current_password" value="当前密码" />
                <P2PTextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    placeholder="输入当前密码"
                    autocomplete="current-password"
                    :variant="form.errors.current_password ? 'error' : 'default'"
                />
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password" value="新密码" />
                <P2PTextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    placeholder="输入新密码"
                    autocomplete="new-password"
                    :variant="form.errors.password ? 'error' : 'default'"
                />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password_confirmation" value="确认新密码" />
                <P2PTextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="确认新密码"
                    autocomplete="new-password"
                    :variant="form.errors.password_confirmation ? 'error' : 'default'"
                />
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                密码修改成功
            </ActionMessage>

            <P2PButton type="submit" variant="primary" :loading="form.processing" :disabled="form.processing">
                保存
            </P2PButton>
        </template>
    </FormSection>
</template>
