<template>
    <div class="relative">
        <!-- Emoji Button -->
        <button
            type="button"
            @click="togglePicker"
            :class="[
                'p-2 rounded transition-colors',
                disabled
                    ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer'
            ]"
            :disabled="disabled"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>

        <!-- Desktop: Dropdown -->
        <div v-if="!isMobile">
            <!-- Click outside overlay -->
            <div v-if="showPicker" class="fixed inset-0 z-40" @click="closePicker"></div>

            <!-- Emoji Picker Dropdown -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="showPicker"
                    class="absolute z-50 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 shadow-lg"
                    :class="[
                        placement === 'top' ? 'bottom-full mb-2' : 'top-full mt-2',
                        'right-0 md:left-0'
                    ]"
                    style="width: 320px"
                >
                    <!-- Emoji Categories -->
                    <div class="border-b border-gray-200 dark:border-gray-700 p-2">
                        <div class="flex gap-1 overflow-x-auto">
                            <button
                                v-for="category in categories"
                                :key="category.name"
                                type="button"
                                @click.stop.prevent="activeCategory = category.name"
                                :class="[
                                    'px-3 py-1.5 rounded text-xs font-medium transition-colors whitespace-nowrap',
                                    activeCategory === category.name
                                        ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'
                                ]"
                            >
                                {{ category.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Emoji Grid -->
                    <div class="p-2 max-h-64 overflow-y-auto">
                        <div class="grid grid-cols-8 gap-1">
                            <button
                                v-for="emoji in currentEmojis"
                                :key="emoji"
                                type="button"
                                @click.stop.prevent="selectEmoji(emoji)"
                                class="p-2 text-xl hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors"
                                :title="emoji"
                            >
                                {{ emoji }}
                            </button>
                        </div>
                    </div>

                    <!-- Recent Emojis -->
                    <div v-if="recentEmojis.length > 0" class="border-t border-gray-200 dark:border-gray-700 p-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">最近使用</p>
                        <div class="flex gap-1 flex-wrap">
                            <button
                                v-for="emoji in recentEmojis"
                                :key="emoji"
                                type="button"
                                @click.stop.prevent="selectEmoji(emoji)"
                                class="p-2 text-xl hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors"
                            >
                                {{ emoji }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>

        <!-- Mobile: Drawer -->
        <P2PMobileDrawer
            v-if="isMobile"
            v-model="showPicker"
            title="选择表情"
            position="bottom"
            size="default"
            :scrollable="false"
        >
            <template #header-icon>
                <div class="p-2 rounded bg-amber-100 dark:bg-amber-900/30">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </template>

            <div class="flex flex-col h-full">
                <!-- Recent Emojis -->
                <div v-if="recentEmojis.length > 0" class="px-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">最近使用</p>
                    <div class="flex gap-2 flex-wrap">
                        <button
                            v-for="emoji in recentEmojis"
                            :key="emoji"
                            type="button"
                            @click.stop.prevent="selectEmoji(emoji)"
                            class="p-2 text-2xl hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors"
                        >
                            {{ emoji }}
                        </button>
                    </div>
                </div>

                <!-- Emoji Categories -->
                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <button
                            v-for="category in categories"
                            :key="category.name"
                            type="button"
                            @click.stop.prevent="activeCategory = category.name"
                            :class="[
                                'px-3 py-1.5 rounded text-sm font-medium transition-colors whitespace-nowrap',
                                activeCategory === category.name
                                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'
                                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'
                            ]"
                        >
                            {{ category.label }}
                        </button>
                    </div>
                </div>

                <!-- Emoji Grid -->
                <div class="flex-1 overflow-y-auto px-4 py-3">
                    <div class="grid grid-cols-6 gap-2">
                        <button
                            v-for="emoji in currentEmojis"
                            :key="emoji"
                            type="button"
                            @click.stop.prevent="selectEmoji(emoji)"
                            class="p-3 text-2xl hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors active:scale-95"
                        >
                            {{ emoji }}
                        </button>
                    </div>
                </div>
            </div>
        </P2PMobileDrawer>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import P2PMobileDrawer from './P2PMobileDrawer.vue';

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    placement: {
        type: String,
        default: 'bottom', // top, bottom
        validator: (value) => ['top', 'bottom'].includes(value)
    }
});

const emit = defineEmits(['select']);

const showPicker = ref(false);
const activeCategory = ref('smileys');
const recentEmojis = ref([]);
const isMobile = ref(false);

// Emoji categories with common emojis
const categories = [
    {
        name: 'smileys',
        label: '表情',
        emojis: ['😀', '😃', '😄', '😁', '😅', '😂', '🤣', '😊', '😇', '🙂', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '😵', '🤯', '🤠', '😎', '🤓', '🧐', '😕', '😟', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖']
    },
    {
        name: 'gestures',
        label: '手势',
        emojis: ['👋', '🤚', '🖐️', '✋', '🖖', '👌', '🤌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👍', '👎', '✊', '👊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💪', '🦾', '🦿', '🦵', '🦶', '👂', '🦻', '👃', '🧠', '🫀', '🫁', '🦷', '🦴', '👀', '👁️', '👅', '👄']
    },
    {
        name: 'hearts',
        label: '爱心',
        emojis: ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '☮️', '✝️', '☪️', '🕉️', '☸️', '✡️', '🔯', '🕎', '☯️', '☦️', '🛐', '⛎', '♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐', '♑', '♒', '♓', '🆔', '⚛️']
    },
    {
        name: 'symbols',
        label: '符号',
        emojis: ['✅', '❌', '❓', '❗', '❕', '❔', '⭕', '🚫', '💯', '💢', '♨️', '🚷', '🚯', '🚳', '🚱', '🔞', '📵', '🚭', '‼️', '⁉️', '🔅', '🔆', '〽️', '⚠️', '🚸', '🔱', '⚜️', '🔰', '♻️', '✳️', '❇️', '💹', '💲', '💱', '©️', '®️', '™️', '#️⃣', '*️⃣', '0️⃣', '1️⃣', '2️⃣', '3️⃣', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟', '🔠', '🔡', '🔤', '🆒', '🆕', '🆖', '🆗', '🆙', '🆚']
    },
    {
        name: 'objects',
        label: '物品',
        emojis: ['📱', '💻', '⌨️', '🖥️', '🖨️', '🖱️', '🖲️', '🕹️', '🗜️', '💾', '💿', '📀', '📼', '📷', '📸', '📹', '🎥', '📽️', '🎞️', '📞', '☎️', '📟', '📠', '📺', '📻', '🎙️', '🎚️', '🎛️', '🧭', '⏱️', '⏲️', '⏰', '🕰️', '⌛', '⏳', '📡', '🔋', '🔌', '💡', '🔦', '🕯️', '🧯', '🛢️', '💸', '💵', '💴', '💶', '💷', '💰', '💳', '💎', '⚖️', '🧰', '🔧', '🔨', '⚒️', '🛠️', '⛏️', '🔩', '⚙️', '🧱', '⛓️', '🧲', '🔫', '💣', '🧨', '🪓', '🔪', '🗡️', '⚔️', '🛡️', '🚬', '⚰️', '⚱️', '🏺', '🔮', '📿', '🧿', '💈', '⚗️', '🔭', '🔬', '🕳️', '🩹', '🩺', '💊', '💉', '🩸', '🧬', '🦠', '🧫', '🧪', '🌡️', '🧹', '🧺', '🧻', '🚽', '🚰', '🚿', '🛁', '🛀', '🧼', '🪒', '🧽', '🧴', '🛎️', '🔑', '🗝️', '🚪', '🪑', '🛋️', '🛏️', '🛌', '🧸', '🖼️', '🛍️', '🛒', '🎁', '🎈', '🎏', '🎀', '🎊', '🎉', '🎎', '🏮', '🎐', '🧧', '✉️', '📩', '📨', '📧', '💌', '📥', '📤', '📦', '🏷️', '📪', '📫', '📬', '📭', '📮', '📯', '📜', '📃', '📄', '📑', '🧾', '📊', '📈', '📉', '🗒️', '🗓️', '📆', '📅', '🗑️', '📇', '🗃️', '🗳️', '🗄️', '📋', '📁', '📂', '🗂️', '🗞️', '📰', '📓', '📔', '📒', '📕', '📗', '📘', '📙', '📚', '📖', '🔖', '🧷', '🔗', '📎', '🖇️', '📐', '📏', '🧮', '📌', '📍', '✂️', '🖊️', '🖋️', '✒️', '🖌️', '🖍️', '📝', '✏️', '🔍', '🔎', '🔏', '🔐', '🔒', '🔓']
    }
];

// Get emojis for current category
const currentEmojis = computed(() => {
    const category = categories.find(c => c.name === activeCategory.value);
    return category ? category.emojis : [];
});

// Check if mobile
const checkMobile = () => {
    isMobile.value = window.innerWidth < 768; // md breakpoint
};

// Toggle picker visibility
const togglePicker = () => {
    if (props.disabled) return;
    showPicker.value = !showPicker.value;
};

// Close picker
const closePicker = () => {
    showPicker.value = false;
};

// Select emoji
const selectEmoji = (emoji) => {
    emit('select', emoji);

    // Add to recent emojis
    const index = recentEmojis.value.indexOf(emoji);
    if (index > -1) {
        recentEmojis.value.splice(index, 1);
    }
    recentEmojis.value.unshift(emoji);
    recentEmojis.value = recentEmojis.value.slice(0, 8);

    // Save to localStorage
    localStorage.setItem('recentEmojis', JSON.stringify(recentEmojis.value));

    closePicker();
};

// Handle escape key
const handleEscape = (e) => {
    if (e.key === 'Escape' && showPicker.value && !isMobile.value) {
        closePicker();
    }
};

// Load recent emojis from localStorage
onMounted(() => {
    const stored = localStorage.getItem('recentEmojis');
    if (stored) {
        try {
            recentEmojis.value = JSON.parse(stored);
        } catch (e) {
            console.error('Failed to parse recent emojis:', e);
        }
    }

    checkMobile();
    window.addEventListener('resize', checkMobile);
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
    document.removeEventListener('keydown', handleEscape);
});
</script>