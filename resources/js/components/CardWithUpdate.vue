<script setup>
import {useColumnStore} from "../stores/columns";
import {reactive, ref} from "vue";
import {required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import Swal from "sweetalert2";

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
async function deleteRestoreCard(id, column_id, status) {
    Swal.fire({
        title: 'Are you sure?',
        text: status == '0' ? "Do you want to restore this card!" : "Do you want to delete this card!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: status == '0' ? '#4CAF50' : '#d33',
        confirmButtonText: status == '0' ? 'Yes, restore it!' : 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            await store.deleteRestoreCard(id, column_id);
        }
    })
}
</script>

<template>
    <div class="flex">
        <div class="card-title" @click="showModal()">
            {{card.title}}
        </div>
        <div>
            <button class="btn" :class="{ 'btn--danger': status != '0', 'btn--restore': status == '0' }" @click="deleteRestoreCard(card.id, card.column_id, status)">
                <font-awesome-icon v-if="status != '0'" icon="trash-can" />
                <font-awesome-icon v-if="status == '0'" icon="rotate-left" />
            </button>
        </div>
    </div>
    <Modal v-model="isShow" :close="closeModal">
        <div class="modal">
            <h3>Update Card</h3>
            <form @submit.prevent="onSubmit(state, v$)">
                <div class="input-group" :class="{ error: v$.title.$errors.length }">
                    <label>Title</label>
                    <input v-model="state.title">
                    <div class="input-errors" v-for="error of v$.title.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <br>
                <div class="input-group" :class="{ error: v$.description.$errors.length }">
                    <label>Description</label>
                    <textarea rows="6" v-model="state.description"></textarea>
                    <div class="input-errors" v-for="error of v$.description.$errors" :key="error.$uid">
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
