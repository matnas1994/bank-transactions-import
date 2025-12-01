<template>

    <Toolbar>
        <ToolbarTitle>System importu transakcji bankowych</ToolbarTitle>
        <toolbar-actions>
            <button class="btn btn-primary" @click="fileInput.click()">Wyślij plik</button>
            <input type="file" ref="fileInput" class="hidden" @change="handleFile" accept=".csv,.json,.xml" />
        </toolbar-actions>
    </Toolbar>

    <div class="p-4">
        <div v-if="table">
            <table class="table">
                <thead>
                    <tr>
                        <th v-for="col in columns" :key="col.id">
                            {{ col.header }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in table.getRowModel().rows" :key="row.original.id"
                        class="hover:bg-white/5 cursor-pointer" @click="showLogs(row)">
                        <td v-for="cell in row.getVisibleCells()" :key="cell.id">
                            <span v-if="cell.column.id === 'status'" :class="{
                                'badge badge-success': cell.getValue() === 'success',
                                'badge badge-warning': cell.getValue() === 'partial',
                                'badge badge-error': cell.getValue() === 'failed'
                            }">
                                {{
                                    STATUS_LABELS[cell.getValue()]
                                }}
                            </span>
                            <span v-else>
                                {{ cell.getValue() }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :current-page="currentPage" :total-pages="totalPages" @go-page="fetchImports" />

        </div>
        <div v-else>
            <span class="loading loading-spinner loading-lg"></span>
        </div>
    </div>



    <ImportLogsDialog ref="logsDialog" :importData="selectedImport" :logs="logs" @close="selectedImport = null" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { createColumnHelper, useVueTable, getCoreRowModel } from '@tanstack/vue-table'
import ImportLogsDialog from '@/components/ImportLogsDialog.vue'
import Pagination from '@/components/ui/Pagination.vue'
import axios from 'axios'
import Toolbar from "../components/ui/toolbar/Toolbar.vue";
import ToolbarTitle from "../components/ui/toolbar/ToolbarTitle.vue";
import ToolbarActions from "../components/ui/toolbar/ToolbarActions.vue";
import { toast } from 'vue3-toastify'

const fileInput = ref(null)
const file = ref(null)
const imports = ref([])
const uploading = ref(false)
const errorMessage = ref('')
const selectedImport = ref(null)
const logsDialog = ref(null)
const logs = ref([])
const currentPage = ref(1)
const totalPages = ref(1)
const perPage = 1;

const columnHelper = createColumnHelper()
const columns = [
    columnHelper.accessor('file_name', { header: 'Nazwa pliku', cell: info => info.getValue() }),
    columnHelper.accessor('total_records', { header: 'Liczba rekordów', cell: info => info.getValue() }),
    columnHelper.accessor('status', {
        header: 'Status',
        cell: info => info.getValue()
    })
]


const STATUS_LABELS = {
    success: 'Sukces',
    partial: 'Częściowo',
    failed: 'Niepowodzenie',
}

const table = useVueTable({
    get data() {
        return imports.value
    },
    columns,
    getCoreRowModel: getCoreRowModel(),
})

const fetchImports = async (page = 1) => {
    try {
        const res = await axios.get('/api/imports'
            , {
                params: { page, per_page: perPage }
            })

        imports.value = res.data.items
        currentPage.value = res.data.pagination.current_page
        totalPages.value = res.data.pagination.total_pages

    } catch (err) {
        console.error(err)
        errorMessage.value = 'Nie udało się pobrać importów'
    }
}

const showLogs = async (row) => {
    const id = row.original.id
    const res = await axios.get(`/api/imports/${id}`)

    selectedImport.value = res.data
    logs.value = res.data.logs

    logsDialog.value.open()
}

const handleFile = async (event) => {
    file.value = event.target.files[0]
    if (!file.value) return

    fileInput.value.value = ''

    const toastId = toast.loading(`Przetwarzanie pliku: ${file.name}`, {
        autoClose: false,
        closeButton: false,
        position: 'bottom-right'
    });

    const formData = new FormData()
    formData.append('file', file.value)

    uploading.value = true
    errorMessage.value = ''

    try {
        const res = await axios.post('/api/imports', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })
        // dodaj nowy import do tabeli
        imports.value = [res.data, ...imports.value]

        toast.update(toastId, {
            render: `Import zakończony: ${res.data.file_name}`,
            type: 'success',
            isLoading: false,
            autoClose: 3000, // toast automatycznie zniknie po 3s
            closeButton: true
        })

    } catch (err) {
        console.error(err)
        toast.update(toastId, {
            render: err.response?.data?.message || 'Nie udało się zaimportować pliku',
            type: 'error',
            isLoading: false,
            autoClose: 5000,
            closeButton: true
        })

    } finally {
        uploading.value = false
        file.value = null
    }
}

onMounted(fetchImports)
</script>
