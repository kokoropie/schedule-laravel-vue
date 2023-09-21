<script setup>
import { ref } from 'vue';
import copy from 'copy-to-clipboard';

const props = defineProps({
    text: {
        required: true,
        type: String,
    },
    delay: {
        type: Number,
        default: 2000
    }
});

const isCopied = ref(false);
const doCopy = () => {
    copy(props.text);
    isCopied.value = true;
    setTimeout(() => {
        isCopied.value = false;
    }, props.delay);
};
</script>
<template>
    <div @click="doCopy">
        <slot v-if="isCopied" name="copied" />
        <slot v-else />
    </div>
</template>
