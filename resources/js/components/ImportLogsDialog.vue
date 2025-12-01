<template>
    <dialog id="logsDialog" class="modal">
        <div class="modal-box max-w-3xl">
            <h3 class="font-bold text-xl mb-4">
                Logi błędów — {{ importData?.file_name }}
            </h3>

            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Komunikat błędu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="log in logs" :key="log.id">
                        <td>{{ log.transaction_id }}</td>
                        <td>{{ log.error_message }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="modal-action">
                <button class="btn" @click="close">Zamknij</button>
            </div>
        </div>
    </dialog>
</template>

<script setup>
const props = defineProps({
    importData: Object,
    logs: Array
})


const emit = defineEmits(['close'])

const close = () => {
    document.getElementById('logsDialog').close()
    emit('close')
}

const open = () => {
    document.getElementById('logsDialog').showModal()
}

defineExpose({ open })
</script>
