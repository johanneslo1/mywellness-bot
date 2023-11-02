<template>
    <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ title }}</label>
    <select :multiple="multiple" v-model="value" :id="id"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <slot />
    </select>
</template>

<script setup>
import {ref, watch} from "vue";

const props = defineProps({
    title: String,
    id: String,
    modelValue: null,
    multiple: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const value = ref(null);

watch(() => props.modelValue, (newValue) => {
    value.value = newValue;
})

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
})
</script>

<style scoped>

</style>
