<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import P2PDialog from '@/Components/UI/P2PDialog.vue';
import InputError from '@/Components/InputError.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PTextInput from '@/Components/UI/P2PTextInput.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            登录设备
        </template>

        <template #description>
            管理您在其他浏览器和设备上的活动会话
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                如有必要，您可以退出所有设备上的其他浏览器会话。下面列出了您最近的一些会话，但此列表可能不完整。如果您认为账户已被盗用，还应该更新密码。
            </div>

            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="mt-5 space-y-6">
                <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
                    <div>
                        <svg v-if="session.agent.is_desktop" class="size-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>

                        <svg v-else class="size-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>

                    <div class="ms-3">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ session.agent.platform ? session.agent.platform : '未知' }} - {{ session.agent.browser ? session.agent.browser : '未知' }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span v-if="session.is_current_device" class="text-green-500 font-semibold">当前设备</span>
                                <span v-else>最后活动 {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-5">
                <P2PButton variant="primary" @click="confirmLogout">
                    退出其他浏览器会话
                </P2PButton>

                <ActionMessage :on="form.recentlySuccessful" class="ms-3">
                    完成
                </ActionMessage>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <P2PDialog
                :show="confirmingLogout"
                title="退出其他浏览器会话"
                icon-color="amber"
                confirm-text="退出其他会话"
                cancel-text="取消"
                @confirm="logoutOtherBrowserSessions"
                @close="closeModal"
            >
                <div class="text-left">
                    <p class="mb-4 text-gray-700 dark:text-gray-300">请输入您的密码以确认您要退出所有设备上的其他浏览器会话。</p>

                    <P2PTextInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="密码"
                        autocomplete="current-password"
                        @keyup.enter="logoutOtherBrowserSessions"
                        :variant="form.errors.password ? 'error' : 'default'"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
            </P2PDialog>
        </template>
    </ActionSection>
</template>
