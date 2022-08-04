<script setup>
import Draggable from "vuedraggable";
import AddCardModal from "./AddCardModal.vue";
import CardWithUpdate from "./CardWithUpdate.vue";
import {useColumnStore} from "../stores/columns";
import {computed, onMounted} from "vue";
import AddColumn from "./AddColumn.vue";
import axios from "axios"
import Swal from "sweetalert2";

const store = useColumnStore();
const status = window.laravel.status;
onMounted(() => {
    store.fetchColumns();
})
const updateColumns = () => {
    store.updateColumns();
};
const deleteColumn = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this column!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            store.deleteColumn(id);
        }
    })
};
const exportSql = async () => {
    await axios.get(window.laravel.api_url + '/export-sql?access_token=' + window.laravel.access_token).then(response => {
        if (response.data.file_url) {
            downloadItem(window.laravel.url + response.data.file_url + '?access_token=' + window.laravel.access_token, 'dump.sql');
        } else {
            Swal.fire({
                title: 'Can\'t export the database',
                text: "",
                icon: 'error',
            })
        }
    });
};

function downloadItem(url, label) {
    axios.get(url, {responseType: 'blob'})
        .then(response => {
            const blob = new Blob([response.data])
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = label
            link.click()
            URL.revokeObjectURL(link.href)
        }).catch(console.error)
}

const columns = computed(() => {
    return store.columns
})
</script>

<template>
    <div class="main-container">
        <div class="fixed-btns">
            <button class="btn btn--primary mx-5px" @click="exportSql">
                <font-awesome-icon icon="file" /> Export SQL
            </button>
            <AddColumn></AddColumn>
        </div>
        <div class="row">
            <div class="column-container" v-for="column in columns" :key="column.id">
                <Draggable
                    @change="updateColumns"
                    :id="`column${column.id}`"
                    data-source="juju"
                    :list="column.cards"
                    class="list-group"
                    group="a"
                    item-key="title"
                >
                    <template #item="{ element }">
                        <div class="list-group-item">
                            <CardWithUpdate :card="element"></CardWithUpdate>
                        </div>
                    </template>

                    <template #header>
                        <div class="column-title flex">
                            <h3>{{ column.title }}</h3>
                            <button class="btn btn--danger" @click="deleteColumn(column.id)">
                                <font-awesome-icon icon="trash-can" />
                            </button>
                        </div>
                    </template>

                    <template #footer>
                        <AddCardModal v-if="status != '0'" :column="column"></AddCardModal>
                    </template>
                </Draggable>
            </div>
        </div>
    </div>
</template>
