<script setup>
import {useColumnStore} from "../stores/columns";
import {reactive, ref} from "vue";
import {required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

const props = defineProps({
    card: Object,
});
const initialState = {
    title: props.card.title,
    description: props.card.description,
};
const state = reactive({...initialState})
const rules = {
    title: {required},
    description: {required},
}
const status = window.laravel.status;
const v$ = useVuelidate(rules, state)
const isShow = ref(false);
const store = useColumnStore();
const showModal = () => {
    isShow.value = true;
}
const closeModal = () => {
    Object.assign(state, initialState);
    isShow.value = false;
}
async function onSubmit(values, v$) {
    if (!await v$.$validate()) return;
    values.id = props.card.id;
    values.column_id = props.card.column_id;
    await store.updateCard(values);
    isShow.value = false;
}
async function deleteRestoreCard(id, column_id) {
    await store.deleteRestoreCard(id, column_id);
}
</script>

<template>
    <div @click="showModal()">
        {{card.title}}
    </div>
    <button @click="deleteRestoreCard(card.id, card.column_id)">{{ status == '0' ? 'Restore' : 'Delete' }}</button>
    <Modal v-model="isShow" :close="closeModal">
        <div class="modal">
            <form @submit.prevent="onSubmit(state, v$)">
                <div :class="{ error: v$.title.$errors.length }">
                    <input v-model="state.title">
                    <div class="input-errors" v-for="error of v$.title.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <br>
                <div :class="{ error: v$.description.$errors.length }">
                    <textarea v-model="state.description"></textarea>
                    <div class="input-errors" v-for="error of v$.description.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <br>
                <button>Submit</button>
                <button type="button" @click="closeModal">Close</button>
            </form>
        </div>
    </Modal>
</template>


<style scoped></style>
