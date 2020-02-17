<template>
    <div class="jumbotron">
        <form>
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">name</label>
                <div class="col-sm-10">
                <input 
                    type="text" 
                    class="form-control" 
                    id="name"
                    v-model="data.name"     
                >
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password"
                    v-model="data.password"    
                >
                </div>
            </div>
            <input @click.prevent="login()" type="button" class="btn btn-primary col-sm-12" value="Login">
        </form>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            data: {
                name: '',
                password: ''
            }
        }
    },
    created() {

    },
    methods: {
        login() {
            axios.post('/api/v1/auth/login', this.data)
                .then(res => {
                    // console.log(res)
                    let token = res.data.token;
                    let role = res.data.role
                    // console.log(token)
                    localStorage.setItem('token', token);
                    localStorage.setItem('role', role);
                    this.$router.push('/')
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>