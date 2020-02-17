<template>
    <div class="container">
    <app-header></app-header>
        <h1>Branch</h1>
        <input name="text" id="name" v-model="branch.name">
        <button class="btn btn-primary" v-if="!is_edit" @click="inputBranch()" >Add</button>
        <button class="btn btn-warning" v-if="is_edit" @click="updateBranch()">Update</button>

        <table class="table table-hover">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <tr v-for="(b, index) in branches" :key="b.id">
                <td>{{ index + 1 }}</td>
                <td>{{ b.name }}</td>
                <td>
                    <button class="btn btn-success" @click="editBranch(b.id)">Edit</button>
                    <button class="btn btn-danger" @click="deleteBranch(b.id)">Hapus</button>
                </td>
            </tr>
        </table>
    </div>
</template>  

<script>
import axios from 'axios'
import Header from './Header.vue';

export default {
    components: {
        'app-header' : Header
    },
    data() {
        return {
            branches: [],
            branch: {
                name: ''
            },
            is_edit: 0
        }
    },
    created() {
        this.fetchBranch();
    },
    methods: {
        fetchBranch() {
            axios.get('/api/v1/branches')
                .then(res => {
                    // console.log(res.data.data)
                    this.branches = res.data.data;
                })
                .catch(err => {
                    console.log(err => {
                        // console.log(err);
                    })
                })
        },
        inputBranch() {
            axios.post('/api/v1/branches', this.branch)
                .then(res => {
                    // console.log(res)
                    this.fetchBranch();
                    this.branch.name = ''
                })
                .catch(err => {
                    // console.log(err)
                })
        },
        editBranch(id) {
            localStorage.setItem('id', id);
            this.is_edit = 1;
        },
        updateBranch() {
            var id = localStorage.getItem('id');
            axios.put('/api/v1/branches/'+id, this.branch)
                .then(res => {
                    this.fetchBranch();
                    this.is_edit = 0;
                    this.branch.name = ''
                    // console.log(res)
                })
                .catch(err => {
                    // console.log(err)
                })
        },
        deleteBranch(id) {
            axios.delete('/api/v1/branches/'+id)
                .then(res => {
                    // console.log(res)
                    this.fetchBranch();
                })
                .catch(err => {
                    // console.log(err)
                })
        }
    }
}
</script>