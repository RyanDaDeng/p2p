<template>
    <div class="relative group">
        <!-- Image Attachment -->
        <div v-if="isImage" class="relative">
            <!-- Thumbnail -->
            <div
                @click="openPreview"
                class="relative overflow-hidden rounded-lg cursor-pointer transition-all hover:opacity-90"
                :style="`width: ${thumbnailWidth}; height: ${thumbnailHeight};`"
            >
                <img
                    :src="attachment.url"
                    :alt="attachment.name"
                    class="w-full h-full object-cover"
                />

                <!-- Overlay with actions - visible on desktop hover, always visible on mobile -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:opacity-0 md:group-hover:opacity-100 transition-opacity flex items-end justify-between p-2">
                    <button
                        @click.stop="openPreview"
                        class="p-1.5 md:p-2 bg-white/90 backdrop-blur-sm rounded-full text-gray-700 hover:bg-white transition-colors"
                        title="查看"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    <a
                        :href="attachment.url"
                        download
                        @click.stop
                        class="p-1.5 md:p-2 bg-white/90 backdrop-blur-sm rounded-full text-gray-700 hover:bg-white transition-colors"
                        title="下载"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- File info (optional) -->
            <div v-if="showInfo" class="mt-1">
                <p class="text-xs truncate" :class="infoTextClass">{{ attachment.name }}</p>
                <p class="text-xs" :class="infoSizeClass">{{ formatFileSize(attachment.size) }}</p>
            </div>
        </div>

        <!-- PDF/Document Attachment -->
        <div v-else-if="isPDF"
             class="flex items-center gap-2 md:gap-3 p-2.5 md:p-3 rounded-lg transition-colors"
             :class="documentBgClass"
        >
            <div class="flex-shrink-0" @click="openDocument">
                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0" @click="openDocument">
                <p class="text-xs md:text-sm font-medium truncate cursor-pointer" :class="documentTextClass">{{ attachment.name }}</p>
                <p class="text-xs" :class="documentSizeClass">PDF · {{ formatFileSize(attachment.size) }}</p>
            </div>
            <div class="flex gap-1">
                <button
                    @click="openDocument"
                    class="p-1.5 rounded-lg bg-black/5 hover:bg-black/10 transition-colors md:hidden"
                    title="查看"
                >
                    <svg class="w-4 h-4" :class="documentIconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                <a
                    :href="attachment.url"
                    download
                    @click.stop
                    class="p-1.5 rounded-lg bg-black/5 hover:bg-black/10 transition-colors"
                    title="下载"
                >
                    <svg class="w-4 h-4" :class="documentIconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Other File Types -->
        <div v-else
             class="flex items-center gap-2 md:gap-3 p-2.5 md:p-3 rounded-lg transition-colors"
             :class="documentBgClass"
        >
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs md:text-sm font-medium truncate" :class="documentTextClass">{{ attachment.name }}</p>
                <p class="text-xs" :class="documentSizeClass">{{ getFileType(attachment.type) }} · {{ formatFileSize(attachment.size) }}</p>
            </div>
            <a
                :href="attachment.url"
                download
                @click.stop
                class="flex-shrink-0 p-1.5 rounded-lg bg-black/5 hover:bg-black/10 transition-colors"
                title="下载"
            >
                <svg class="w-4 h-4" :class="documentIconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </a>
        </div>

        <!-- Image Preview Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showImagePreview"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm p-4"
                     @click="closePreview"
                >
                    <div class="relative max-w-[90vw] max-h-[90vh] flex flex-col">
                        <img
                            :src="attachment.url"
                            :alt="attachment.name"
                            class="max-w-full max-h-[80vh] md:max-h-[90vh] object-contain"
                            @click.stop
                        />

                        <!-- Mobile bottom controls -->
                        <div class="flex justify-center gap-4 mt-4 md:hidden">
                            <button
                                @click="closePreview"
                                class="p-3 bg-white/90 backdrop-blur-sm rounded-full text-gray-700 active:bg-white transition-colors"
                                title="关闭"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <a
                                :href="attachment.url"
                                download
                                class="p-3 bg-white/90 backdrop-blur-sm rounded-full text-gray-700 active:bg-white transition-colors"
                                title="下载原图"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>

                        <!-- Desktop Close button -->
                        <button
                            @click="closePreview"
                            class="hidden md:block absolute top-4 right-4 p-2 bg-black/50 hover:bg-black/70 rounded-full text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Desktop Download button -->
                        <a
                            :href="attachment.url"
                            download
                            class="hidden md:block absolute bottom-4 right-4 p-3 bg-white/90 hover:bg-white rounded-full text-gray-700 transition-colors"
                            title="下载原图"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    attachment: {
        type: Object,
        required: true,
        validator: (value) => {
            return value.url && value.name && value.type;
        }
    },
    variant: {
        type: String,
        default: 'default', // 'default', 'primary' (for sent messages)
        validator: (value) => ['default', 'primary'].includes(value)
    },
    thumbnailSize: {
        type: String,
        default: 'md', // 'sm', 'md', 'lg'
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    showInfo: {
        type: Boolean,
        default: false
    }
});

// State
const showImagePreview = ref(false);

// Computed
const isImage = computed(() => {
    return props.attachment.type && props.attachment.type.startsWith('image/');
});

const isPDF = computed(() => {
    return props.attachment.type === 'application/pdf';
});

const thumbnailDimensions = {
    sm: { width: '150px', height: '150px' },
    md: { width: '200px', height: '200px' },
    lg: { width: '250px', height: '250px' }
};

const thumbnailWidth = computed(() => thumbnailDimensions[props.thumbnailSize].width);
const thumbnailHeight = computed(() => thumbnailDimensions[props.thumbnailSize].height);

// Style classes based on variant
const documentBgClass = computed(() => {
    return props.variant === 'primary'
        ? 'bg-emerald-500 hover:bg-emerald-400'
        : 'bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600';
});

const documentTextClass = computed(() => {
    return props.variant === 'primary'
        ? 'text-white'
        : 'text-gray-900 dark:text-slate-200';
});

const documentSizeClass = computed(() => {
    return props.variant === 'primary'
        ? 'text-emerald-100'
        : 'text-gray-500 dark:text-slate-400';
});

const documentIconClass = computed(() => {
    return props.variant === 'primary'
        ? 'text-white'
        : 'text-gray-600 dark:text-slate-400';
});

const infoTextClass = computed(() => {
    return props.variant === 'primary'
        ? 'text-white/90'
        : 'text-gray-700 dark:text-slate-300';
});

const infoSizeClass = computed(() => {
    return props.variant === 'primary'
        ? 'text-white/70'
        : 'text-gray-500 dark:text-slate-400';
});

// Methods
const formatFileSize = (bytes) => {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const getFileType = (mimeType) => {
    if (!mimeType) return '文件';
    const types = {
        'application/zip': 'ZIP',
        'application/x-rar-compressed': 'RAR',
        'application/msword': 'Word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'Word',
        'application/vnd.ms-excel': 'Excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'Excel',
        'text/plain': '文本',
        'text/csv': 'CSV'
    };
    return types[mimeType] || '文件';
};

const openPreview = () => {
    if (isImage.value) {
        showImagePreview.value = true;
    }
};

const closePreview = () => {
    showImagePreview.value = false;
};

const openDocument = () => {
    window.open(props.attachment.url, '_blank');
};
</script>