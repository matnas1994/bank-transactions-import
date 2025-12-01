<template>
    <div class="flex items-center space-x-1 mt-4">
        <button class="btn btn-sm" :disabled="currentPage === 1" @click="goPage(currentPage - 1)">
            Poprzednia
        </button>

        <!-- Pierwsza strona -->
        <button v-if="visiblePages[0] > 1" class="btn btn-sm" @click="goPage(1)">1</button>

        <!-- Elipsa przed -->
        <span v-if="visiblePages[0] > 2" class="px-2">...</span>

        <!-- Widoczne strony -->
        <button
            v-for="page in visiblePages"
            :key="page"
            class="btn btn-sm"
            :class="{ 'btn-active': currentPage === page }"
            @click="goPage(page)"
        >
            {{ page }}
        </button>

        <!-- Elipsa po -->
        <span v-if="visiblePages[visiblePages.length - 1] < totalPages - 1" class="px-2">...</span>

        <!-- Ostatnia strona -->
        <button v-if="visiblePages[visiblePages.length - 1] < totalPages" class="btn btn-sm" @click="goPage(totalPages)">
            {{ totalPages }}
        </button>

        <!-- Następna -->
        <button class="btn btn-sm" :disabled="currentPage === totalPages" @click="goPage(currentPage + 1)">
            Następna
        </button>
    </div>
</template>

<script setup>
import { computed, defineProps, defineEmits } from 'vue'

const props = defineProps({
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true },
    maxVisiblePages: { type: Number, default: 5 }
})

const emit = defineEmits(['go-page'])

const visiblePages = computed(() => {
    const pages = []
    let start = Math.max(props.currentPage - Math.floor(props.maxVisiblePages / 2), 1)
    let end = start + props.maxVisiblePages - 1

    if (end > props.totalPages) {
        end = props.totalPages
        start = Math.max(end - props.maxVisiblePages + 1, 1)
    }

    for (let i = start; i <= end; i++) pages.push(i)
    return pages
})

const goPage = (page) => {
    if (page !== props.currentPage) emit('go-page', page)
}
</script>
