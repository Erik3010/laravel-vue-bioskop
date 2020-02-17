<template>
    <div class="container">
        <app-header></app-header>
        <div>
            <form>
                <div class="form-group">
                    <label for="movieID">Movie ID</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="movieID"
                        v-model="schedule.movie_id"
                    >
                    <label for="studioID">Studio ID</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="studioID"
                        v-model="schedule.studio_id"
                    >
                    <label for="startTime">Start Time</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="startTime"
                        v-model="schedule.start"
                    >
                </div>
                <button class="btn btn-primary" v-if="!is_edit" @click.prevent="createSchedule">Add</button>
                <button class="btn btn-warning" v-if="is_edit" @click.prevent="updateSchedule">Update</button>
            </form>
        </div>

        <div class="mt-5">
            <table class="table">
                <thead class="thead-dark">
                    <th>No</th>
                    <th>Movie ID</th>
                    <th>Studio ID</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr v-for="(schedule, index) in schedules" :key="index" >
                        <td>{{ index + 1 }}</td>
                        <td>{{ schedule.movie_id }}</td>
                        <td>{{ schedule.studio_id }}</td>
                        <td>{{ schedule.start }}</td>
                        <td>{{ schedule.end }}</td>
                        <td>{{ schedule.price }}</td>
                        <td>
                            <button class="btn btn-success" @click="editSchedule(schedule.id)" >Edit</button>
                            <button class="btn btn-danger" @click="deleteSchedule(schedule.id)" >Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import Header from './Header.vue';

export default {
    components: {
        'app-header': Header
    },
    data() {
        return {
            schedule: {
                'movie_id' : '',
                'studio_id' : '',
                'start' : ''
            },
            schedules: [],
            is_edit: 0
        }
    },
    created() {
        this.fetchSchedule();
    },
    methods: {
        fetchSchedule() {
            let token = localStorage.getItem('token');

            axios.get('api/v1/schedules?token='+token)
                .then(res => {
                    this.schedules = res.data.data;
                    // console.log(this.schedules);
                })
                .catch(err => {
                    console.log(err);
                })

        },
        createSchedule() {
            let token = localStorage.getItem('token');

            axios.post('api/v1/schedules?token='+token, this.schedule)
                .then(res => {
                    // console.log(res)
                    this.fetchSchedule();
                    if(res.data.message=="overlapped") {
                        alert('start time overlapped');
                    }
                })
                .catch(err => {
                    console.log(err)
                })
        },
        editSchedule(id) {
            localStorage.setItem('id', id);
            this.is_edit = 1;
        },
        updateSchedule() {
            let token =  localStorage.getItem('token');
            let id = localStorage.getItem('id');

            axios.put('/api/v1/schedules/'+id+'?token='+token, this.schedule)
                .then(res => {
                    console.log(res);
                    this.fetchSchedule();
                })
                .catch(err => {
                    console.log(err)
                })

        },
        deleteSchedule(id) {
            let token = localStorage.getItem('token');

            axios.delete('/api/v1/schedules/'+id)
                .then(res => {
                    // console.log(res);
                    this.fetchSchedule();
                })
                .catch(err => {
                    console.log(err)
                })
        }
    },
}
</script>