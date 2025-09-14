<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import P2PButton from '@/Components/UI/P2PButton.vue';
import P2PTextInput from '@/Components/UI/P2PTextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    console.log('updateProfileInformation called');
    console.log('Form data:', form.data());

    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    try {
        const updateRoute = route('user-profile-information.update');
        console.log('Update route:', updateRoute);

        form.post(updateRoute, {
            errorBag: 'updateProfileInformation',
            preserveScroll: true,
            onSuccess: () => {
                clearPhotoFileInput();
                console.log('Profile updated successfully');
            },
            onError: (errors) => {
                console.error('Update failed:', errors);
            },
            onFinish: () => {
                console.log('Request finished');
            }
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            基本信息
        </template>

        <template #description>
            更新您的个人资料和邮箱地址
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <InputLabel for="photo" value="头像" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <P2PButton variant="secondary" size="sm" class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    选择新照片
                </P2PButton>

                <P2PButton
                    v-if="user.profile_photo_path"
                    variant="danger"
                    size="sm"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    移除照片
                </P2PButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="姓名" />
                <P2PTextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="输入姓名"
                    required
                    autocomplete="name"
                    :variant="form.errors.name ? 'error' : 'default'"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="邮箱" />
                <P2PTextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="输入邮箱地址"
                    required
                    autocomplete="username"
                    :variant="form.errors.email ? 'error' : 'default'"
                />
                <InputError :message="form.errors.email" class="mt-2" />

                <!-- Email Verification Status -->
                <div class="mt-2">
                    <div v-if="user.email_verified_at" class="flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>邮箱已验证</span>
                    </div>

                    <div v-else-if="$page.props.jetstream.hasEmailVerification" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-amber-700 dark:text-amber-300">
                                    您的邮箱地址尚未验证
                                </p>
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 underline mt-1 inline-block cursor-pointer"
                                    @click.prevent="sendEmailVerification"
                                >
                                    点击重新发送验证邮件
                                </Link>
                            </div>
                        </div>

                        <div v-show="verificationLinkSent" class="mt-2 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>验证链接已发送到您的邮箱</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                保存成功
            </ActionMessage>

            <P2PButton
                type="submit"
                variant="primary"
                :loading="form.processing"
                :disabled="form.processing"
                @click="updateProfileInformation"
            >
                保存
            </P2PButton>
        </template>
    </FormSection>
</template>
