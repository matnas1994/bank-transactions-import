<template>
    <div :class="['flex flex-col flex-wrap gap-px', $props.class]">
        <slot />
    </div>
</template>

<script setup>
defineProps({
    class: { type: String, default: '' },
})
</script>
