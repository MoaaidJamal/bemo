import { defineStore } from 'pinia'
import axios from "axios"
import {reactive} from "vue";

export const useColumnStore = defineStore("columns",{
    state: () => ({
        columns: reactive([]),
    }),
    actions: {
        async fetchColumns() {
            try {
                const data = await axios.get(window.laravel.api_url + '/list-cards?access_token=' + window.laravel.access_token + '&date=' + window.laravel.date + '&status=' + window.laravel.status)
                this.columns = data.data.columns
            }
            catch (error) {
                console.log(error)
            }
        },
        async addColumn(values) {
            try {
                const data = await axios.post(window.laravel.api_url + '/columns/store?access_token=' + window.laravel.access_token, values)
                this.columns[data.data.column.id] = data.data.column
            }
            catch (error) {
                console.log(error)
            }
        },
        async deleteColumn(id) {
            try {
                await axios.delete(window.laravel.api_url + '/columns/' + id + '?access_token=' + window.laravel.access_token)
                delete this.columns[id];
            }
            catch (error) {
                console.log(error)
            }
        },
        async updateColumns() {
            try {
                const data = await axios.put(window.laravel.api_url + '/columns?access_token=' + window.laravel.access_token + '&date=' + window.laravel.date + '&status=' + window.laravel.status, {columns: this.columns})
                this.columns = data.data.columns
            }
            catch (error) {
                console.log(error)
            }
        },
        async addCard(values) {
            try {
                values.access_token = window.laravel.access_token;
                const data = await axios.post(window.laravel.api_url + '/cards/store', values)
                this.columns[values.column_id].cards.push(data.data.card)
            }
            catch (error) {
                console.log(error)
            }
        },
        async updateCard(card) {
            try {
                card.access_token = window.laravel.access_token;
                const data = await axios.put(window.laravel.api_url + '/cards/' + card.id, card);
                let index = this.columns[card.column_id].cards.findIndex(obj => obj.id === card.id);
                if (index !== undefined) {
                    this.columns[card.column_id].cards[index] = data.data.card;
                }
            }
            catch (error) {
                console.log(error)
            }
        },
        async deleteRestoreCard(id, column_id) {
            try {
                await axios.post(window.laravel.api_url + '/cards/delete-restore/' + id, {
                    'access_token': window.laravel.access_token,
                    'status': window.laravel.status
                })
                let index = this.columns[column_id].cards.findIndex(obj => obj.id === id);
                if (index !== undefined) {
                    this.columns[column_id].cards.splice(index, 1);
                    if (!this.columns[column_id].cards.length) {
                        delete this.columns[column_id];
                    }
                }
            }
            catch (error) {
                console.log(error)
            }
        },
    },
})
