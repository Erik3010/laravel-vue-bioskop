<template>
    <div class="container">
        <app-header></app-header>
        <div>
            <form>
                <div class="form-group">
                    <label for="name">Movie Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name"
                        v-model="movie.name"
                    >
                </div>
                <div class="form-group">
                    <label for="minute">Minute length</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="minute"
                        v-model="movie.minute_length"
                    >
                </div>
                <div class="form-group">
                    <label for="picture">Picture</label>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="picture"
                        @change="uploadImage"
                    >
                </div>
                <button class="btn btn-primary" @click.prevent="createMovie" v-if="!is_edit">Add</button>
                <button class="btn btn-warning" @click.prevent="updateMovie" v-if="is_edit" >Update</button>
            </form>
        </div>
        <div class="mt-5">
            <table class="table">
                <thead class="thead-dark">
                    <th>No</th>
                    <th>Movie Name</th>
                    <th>Minute Length</th>
                    <th>Picture</th>
                    <th>Action</th>
                </thead>
                <tbody>
                     <tr v-for="(movie, index) in movies" :key="movie.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ movie.name }}</td>
                        <td>{{ movie.minute_length }} minutes </td>
                        <td>
                            <img :src="'./images/' + movie.picture_url" alt="no image" class="img-fluid" style="max-width: 100px;">
                        </td>
                        <td>
                            <button class="btn btn-success" @click="editMovie(movie.id)" >Edit</button>
                            <button class="btn btn-danger" @click="deleteMovie(movie.id)" >Delete</button>
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
        'app-header' : Header
    },
    data() {
        return {
            movie: {
                'name': '',
                'minute_length': '',
                'picture' : ''
            },
            movies: [],
            is_edit: 0
        }
    },
    created() {
        this.fetchMovie();
    },
    methods: {
        uploadImage(event) {
            this.movie.picture = event.target.files[0];
            console.log(this.movie.picture);
        },
        fetchMovie() {
            let token = localStorage.getItem('token');

            axios.get('/api/v1/movies?token='+token)
                .then(res => {
                    this.movies = res.data.data;
                    // console.log(this.movies)
                })
                .catch(err => {
                    console.log(err)
                })
        },
        createMovie() {
            let token = localStorage.getItem('token');

            let formData = new FormData();
            for(let key in this.movie){
                formData.append(key, this.movie[key]);
            }

            axios.post('/api/v1/movies?token='+token, formData)
                .then(res => {
                    // console.log(res)
                    this.fetchMovie();
                })
                .catch(err => {
                    console.log(err)
                })
        },
        editMovie(id) {
            localStorage.setItem('id', id);
            this.is_edit = 1;
        },
        updateMovie() {
            let token = localStorage.getItem('token');
            let id = localStorage.getItem('id');

            let formData = new FormData();
            for(let key in this.movie) {
                formData.append(key, this.movie[key]);
                formData.append('_method', 'PUT');
            }

            axios.post('/api/v1/movies/'+id+'?token='+token, formData)
                .then(res => {
                    console.log(res)
                    this.fetchMovie()
                })
                .catch(err => {
                    console.log(err)
                })

        },
        deleteMovie(id) {
            let token = localStorage.getItem('token');

            axios.delete('api/v1/movies/'+id)
                .then(res => {
                    // console.log(res)
                    this.fetchMovie();
                })
                .catch(err => {
                    // console.log(err)
                })
        }
    }
}
</script>