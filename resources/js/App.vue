<script setup>
import Draggable from "vuedraggable";
import AddCardModal from "./components/AddCardModal.vue";
import CardWithUpdate from "./components/CardWithUpdate.vue";
import {useColumnStore} from "./stores/columns";
import {computed, onMounted} from "vue";
import AddColumn from "./components/AddColumn.vue";
import axios from "axios"

const store = useColumnStore();
const status = window.laravel.status;
onMounted(() => {
    store.fetchColumns();
})
const updateColumns = () => {
    store.updateColumns();
};
const deleteColumn = (id) => {
    store.deleteColumn(id);
};
const exportSql = async () => {
    await axios.get(window.laravel.api_url + '/export-sql?access_token=' + window.laravel.access_token).then(response => {
        if (response.data.file_url) {
            downloadItem(window.laravel.url + response.data.file_url + '?access_token=' + window.laravel.access_token, 'dump.sql');
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
    <div class="row">
        <div class="col-4" v-for="column in columns" :key="column.id">
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
                    <div>
                        <h3>{{ column.title }}</h3>
                        <button @click="deleteColumn(column.id)">Delete</button>
                    </div>
                </template>

                <template #footer>
                    <AddCardModal v-if="status != '0'" :column="column"></AddCardModal>
                </template>
            </Draggable>
        </div>
        <AddColumn></AddColumn>
        <button @click="exportSql">Export SQL Schema</button>
    </div>
</template>
<style scoped></style>
