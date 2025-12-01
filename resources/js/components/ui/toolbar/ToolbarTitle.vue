<template>
    <h1 :class="['font-semibold text-foreground text-lg', $props.class]">
        <slot />
    </h1>
</template>

<script setup>
defineProps({
    class: { type: String, default: '' },
})
</script>
