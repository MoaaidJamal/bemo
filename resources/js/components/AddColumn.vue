<script setup>
import {useColumnStore} from "../stores/columns";
import {reactive, ref} from "vue";
import {required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

const initialState = {
    title: '',
};
const state = reactive({...initialState})
const rules = {
    title: {required},
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
    await store.addColumn(values);
    Object.assign(state, initialState);
    isShow.value = false;
}
</script>

<template>
    <button class="btn btn--success mx-5px" v-if="status != '0'" @click="showModal()">
        <font-awesome-icon icon="plus" /> Add Column
    </button>
    <Modal v-model="isShow" v-show="isShow" :close="closeModal">
        <div class="modal">
            <h3>Add Column</h3>
            <form @submit.prevent="onSubmit(state, v$)">
                <div class="input-group" :class="{ error: v$.title.$errors.length }">
                    <label>Title</label>
                    <input v-model="state.title">
                    <div class="input-errors" v-for="error of v$.title.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <br>
                <button class="btn mx-5px btn--success">Submit</button>
                <button class="btn mx-5px" type="button" @click="closeModal">Close</button>
            </form>
        </div>
    </Modal>
</template>
