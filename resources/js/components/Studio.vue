<template>
    <div class="container">
        <app-header></app-header>
        
        <input name="text" v-model="studio.name">
        <input name="text" v-model="studio.branch_id">
        <input name="text" v-model="studio.basic_price">
        <input name="text" v-model="studio.additional_friday_price">
        <input name="text" v-model="studio.additional_saturday_price">
        <input name="text" v-model="studio.additional_sunday_price">

        <button v-if="!is_edit" class="btn btn-primary" @click="inputStudio()">Add</button>
        <button v-if="is_edit" class="btn btn-warning" @click="updateStudio()">Update</button>

        <table class="table table-hover">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Branch ID</th>
                <th>Basic Price</th>
                <th>Additional Friday Price</th>
                <th>Additional Saturday Price</th>
                <th>Additional Sunday Price</th>
                <th>Action</th>
            </tr>
            <tr v-for="(studio, index) in studios" :key="studio.id">
                <td>{{ index + 1 }}</td>
                <td>{{ studio.name }}</td>
                <td>{{ studio.branch_name }}</td>
                <td>{{ studio.basic_price }}</td>
                <td>{{ studio.additional_friday_price }}</td>
                <td>{{ studio.additional_saturday_price }}</td>
                <td>{{ studio.additional_sunday_price }}</td>
                <td>
                    <button class="btn btn-success" @click="editStudio(studio.id)">Edit</button>
                    <button class="btn btn-danger" @click="deleteStudio(studio.id)">Delete</button>
                </td>
            </tr>
        </table>   


    </div>
</template>

<script>
import axios from 'axios'
import Header from './Header.vue'

export default {
    components: {
        'app-header': Header
    },
    data() {
        return {
            studios: [],
            studio: {
                'name': '',
                'branch_id': '',
                'basic_price': '',
                'additional_friday_price': '',
                'additional_saturday_price': '',
                'additional_sunday_price' : ''
            },
            is_edit: 0
        }
    },
    created() {
        this.fetchStudio();
    },
    methods: {
        fetchStudio() {
            let token = localStorage.getItem('token')
            axios.get('/api/v1/studios?token='+token)
                .then(res => {
                    // console.log(res)
                    this.studios = res.data.data
                })
                .catch(err => {
                    // console.log(err)
                })
        },
        inputStudio() {
            axios.post('/api/v1/studios', this.studio)
                .then(res => {
                    // console.log(res)
                    this.fetchStudio();
                })
                .catch(err => {
                    // console.log(err)
                })
        },
        editStudio(id) {
            localStorage.setItem('id', id);
            this.is_edit = 1;
        },
        updateStudio() {
            var id = localStorage.getItem('id', id);
            axios.put('/api/v1/studios/'+id, this.studio)
                .then(res => {
                    console.log(res)
                    this.fetchStudio();
                })
                .catch(err => {
                    console.log(err)
                })
        },
        deleteStudio(id) {
            axios.delete('/api/v1/studios/'+id)
                .then(res => {
                    // console.log(res)
                    this.fetchStudio();
                })
                .catch(err => {
                    // console.log(err)
                })
        }
    }
}
</script>