<template>
    <div class="relative" :class="$attrs.class">
        <button
            type="button"
            @click="isOpen = !isOpen"
            class="flex items-center gap-1.5 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="disabled"
        >
            <span v-if="selectedCountry" class="text-lg">{{ selectedCountry.flag }}</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ selectedCountry?.phone_code || '+86' }}
            </span>
            <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 mt-1 w-72 bg-white dark:bg-gray-800 rounded shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto"
                @click.stop
            >
                <!-- Search input -->
                <div class="p-2 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="搜索国家/地区..."
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        @click.stop
                    />
                </div>

                <!-- Options -->
                <div class="py-1">
                    <button
                        v-for="country in filteredCountries"
                        :key="country.code"
                        @click="selectCountry(country)"
                        class="w-full flex items-center gap-3 px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                        :class="{
                            'bg-emerald-50 dark:bg-emerald-900/20': selectedCountry?.code === country.code
                        }"
                    >
                        <span class="text-lg">{{ country.flag }}</span>
                        <span class="flex-1 text-left text-gray-700 dark:text-gray-300">
                            {{ country.name_zh }}
                        </span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">
                            {{ country.phone_code }}
                        </span>
                    </button>
                </div>

                <div v-if="!props.countries || props.countries.length === 0" class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    暂无可选国家/地区
                </div>
                <div v-else-if="searchQuery && filteredCountries.length === 0" class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    未找到匹配的国家/地区
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    modelValue: {
        type: String,
        default: '+61'
    },
    countries: {
        type: Array,
        required: true,
        default: () => []
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchQuery = ref('');

const selectedCountry = computed(() => {
    if (!props.countries || props.countries.length === 0) {
        return { flag: '🇦🇺', phone_code: '+61', name_zh: '澳大利亚' };
    }
    return props.countries.find(c => c.phone_code === props.modelValue) ||
           props.countries.find(c => c.phone_code === '+61') ||
           props.countries[0];
});

const filteredCountries = computed(() => {
    if (!props.countries || props.countries.length === 0) {
        return [];
    }

    if (!searchQuery.value) {
        return props.countries;
    }

    const query = searchQuery.value.toLowerCase();
    return props.countries.filter(country =>
        country.name.toLowerCase().includes(query) ||
        country.name_zh.includes(query) ||
        country.phone_code.includes(query)
    );
});

const selectCountry = (country) => {
    emit('update:modelValue', country.phone_code);
    isOpen.value = false;
    searchQuery.value = '';
};

const handleClickOutside = (event) => {
    if (isOpen.value && !event.target.closest('.relative')) {
        isOpen.value = false;
    }
};

const handleEscape = (event) => {
    if (event.key === 'Escape' && isOpen.value) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscape);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscape);
});

watch(isOpen, (newVal) => {
    if (!newVal) {
        searchQuery.value = '';
    }
});
</script>

<style scoped>
/* Light mode scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6; /* gray-100 */
}

::-webkit-scrollbar-thumb {
    background: #9ca3af; /* gray-400 */
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6b7280; /* gray-500 */
}

/* Dark mode scrollbar */
:global(.dark) ::-webkit-scrollbar-track {
    background: #1f2937; /* gray-800 */
}

:global(.dark) ::-webkit-scrollbar-thumb {
    background: #4b5563; /* gray-600 */
}

:global(.dark) ::-webkit-scrollbar-thumb:hover {
    background: #6b7280; /* gray-500 */
}
</style>