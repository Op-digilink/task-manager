<template>
    <!-- navbar section -->
   <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <img src="./rankly.png" alt="">
                <a class="navbar-brand" style="font-family:'Times New Roman', Times, serif" href="#">Rankly</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hi, {{ userName }}
                        </a>
                    </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search task..." id="search" @keyup="searchTask">
                        <button class="btn btn-outline-success" type="button" @click="searchTask">Search</button>
                    </form>
                </div>
                </div>
            </nav>
        </div>
    </div>
    <TaskComponent ref="taskComp"></TaskComponent>
    
</template>

<script>
    import TaskComponent from './TaskComponent.vue';

    export default {
        components: {
            TaskComponent
        },

        data () {
            return {
                userName: "Guest",
                token:"",
                url:"",
            }
        },

        mounted () {
            this.token = this.getToken('token');
            this.url = window.location.origin;
            setTimeout(() => {
                this.getData();
            }, 100);
        },

        methods : {
            searchTask () {
                const searchVal = document.getElementById('search').value;
                this.$refs.taskComp.taskSearch(searchVal);
            },

            getToken(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                else return value;
            },

            getData() {
                const endPoint  = `${this.url}/api/getdata`;

                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }

                axios.get(endPoint, options)
                .then(response => {
                    this.userName = response.data.data.name;
                })
                .catch(error => {
                    console.error('Error fetching task list:', error);
                });
            },

        }
    }   
</script>

