<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-slate-100 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        v-model="model"
        ref="input"
    />
</template>
