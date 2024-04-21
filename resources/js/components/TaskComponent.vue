<template>
    <div class="container">
        <!-- create form -->
        <div class="row justify-content-center py-3 mb-5">
            <div class="col-6" v-show="!taskForm" @click="taskForm = !taskForm">
                <textarea class="form-control shadow-sm py-2" cols="30" rows="1" placeholder="Create a task.."></textarea>
            </div>
            <div class="col-6 border px-0 shadow-sm" v-show="taskForm">
                <textarea class="border-0 form-control shadow-sm py-2" rows="1" placeholder="Title" id="title"></textarea>
                <textarea class="border-0 form-control shadow-sm py-2" autofocus rows="4" placeholder="Description..." id="description"></textarea>
                <input type="datetime-local" class="border-0 form-control" id="deadline">
                <input type="hidden" :value="userId" id="userId">
                <div class="text-muted text-end py-2" style="font-weight: 500;">
                    <small class="pointer mx-2" @click="taskCreate">Save</small>
                    <small class="pointer mx-2" @click="taskForm = !taskForm">Close</small>
                </div>
            </div>
        </div>
        <!-- show message -->
        <div class="row justify-content-center" v-show="messageDiv">
            <div class="col-8">
                <div :class="messageClass" role="alert">
                    {{ messageText }}
                </div>
            </div>
        </div>
        <!-- tasks section -->
        <div class="row justify-content-center pb-5">
            <div class="col-3 m-2" v-for="item in tasks"> 
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">{{ item.title }}</h5>
                    <small class="card-subtitle mb-2 text-muted">{{ item.deadline }}</small>
                    <p class="card-text mt-2">{{ item.description.slice(0, 100) }} {{ item.description.length>100 ? '...':'' }}</p>
                    <a href="#" @click="viewTask(item.id)" data-bs-toggle="modal" data-bs-target="#viewModal" class="btn btn-sm btn-warning text-white">View</a>
                    <a href="#" @click="viewTask(item.id)" data-bs-toggle="modal" data-bs-target="#editModal" class="mx-1 btn btn-sm btn-primary" :data-id="item.id">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger" @click="taskDelete(item.id)">Delete</a>
                    </div>
                </div>
            </div>
        </div>

  

        <!-- view Modal -->
        <div class="modal fade" data-bs-backdrop="static" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title" id="exampleModalLabel">{{ viewModal.title }}</h5>
                    </div>
                    <div class="modal-body text-center py-2">
                        <p>{{ viewModal.description }}</p>
                        <div><small class="text-warning fb">Status:</small> <br><small>{{ status(viewModal.status) }}</small></div>
                        <div><small class="text-warning fb">Deadline: </small> <br><small>{{ viewModal.deadline }}</small></div>
                        <div><small class="text-warning fb">Created At:</small> <br><small>{{ viewModal.created_at }}</small></div>

                        <button type="button" class=" mt-5 btn btn-warning text-white w-100" data-bs-dismiss="modal">Close view</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- update  Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Update {{ viewModal.title }}</h5>
                    </div>
                    <div class="modal-body py-2">
                        <div class="row">
                            <div class="col-12 my-2">
                                <label for="m-title">Title</label><font class="text-danger">*</font>
                                <input type="text" class="form-control" id="m-title">
                            </div>
                            <div class="col-12 my-2">
                                <label for="m-description">Description</label><font class="text-danger">*</font>
                                <textarea class="form-control" id="m-description"  rows="4"></textarea>
                            </div>
                            <div class="col-12 my-2">
                                <label for="m-deadline">Deadline</label><font class="text-danger">*</font>
                                <input type="datetime-local" class="form-control" id="m-deadline">
                            </div>
                            <div class="col-12 my-2">
                                <label for="m-deadline">Status</label><font class="text-danger">*</font>
                                <select class="form-select" id="m-status">
                                    <option value="0">Pending</option>
                                    <option value="1">Stop</option>
                                    <option value="2">Complete</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="mt-5 btn btn-primary text-white w-100" @click="taskUpdate(viewModal.id)" data-bs-dismiss="modal">Update Task</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
 
<script>
    import axios from 'axios';

    export default  {
        data () {
            return {
                token:"",
                url:"",
                tasks: {},
                taskForm : false,
                messageDiv : false,
                messageClass : "alert alert-success",
                messageText : "",
                userData: {},
                userId : '',
                viewModal: {
                    title: "",
                    description: "",
                    deadline: "",
                    status: "",
                    created_at: ""
                }
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

            taskList() {
                const userId = this.userId;
                const endPoint  = `${this.url}/api/task?user=${userId}`;

                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }

                axios.get(endPoint, options)
                .then(response => {
                    this.tasks = response.data.data;
                })
                .catch(error => {
                    console.error('Error fetching task list:', error);
                });
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
                    this.userData = response.data.data;
                    this.userId = response.data.data.id;
                    this.taskList();
                })
                .catch(error => {
                    console.error('Error fetching task list:', error);
                });
            },

            getToken(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                else return value;
            },

            taskSearch(searchVal) {
                const endPoint  = `${this.url}/api/task/search`;
                const userId = this.userId;
                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }
                const postData = {
                    search: searchVal,
                    user: userId,
                }

                axios.post(endPoint, postData, options)
                .then(response => {
                    this.tasks = response.data.data;
                    this.showMessage(response.data.message, response.data.status, true);
                })
                .catch(error => {
                    if (error.response) {
                        const res = error.response.data;
                        this.showMessage(res.message, res.status);
                    } 
                });
            },

            viewTask(id) {
                const endPoint  = `${this.url}/api/task/${id}`;
                const userId = this.userId;
                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }

                axios.get(endPoint, options)
                .then(response => {
                    const data =  response.data.data;
                    this.viewModal = data;
                    // Assign value to edit modal
                    document.getElementById('m-title').value = data.title;
                    document.getElementById('m-description').innerText = data.description;
                    document.getElementById('m-deadline').value = data.deadline;
                    document.getElementById('m-status').value = data.status;
                })
                .catch(error => {
                    if (error.response) {
                        const res = error.response.data;
                        this.showMessage(res.message, res.status);
                    } 
                });
            },

            showMessage(message, status, hide=false) {
                if (status) this.messageClass = "alert alert-success py-2";
                else this.messageClass = "alert alert-danger py-2";
                this.messageText = message;
                this.messageDiv = true;

                if (!hide) {
                    setTimeout(() => {
                        this.messageDiv = false;
                    }, 2500);
                }

            },

            status (id) {
                switch (id) {
                    case "0":
                        return "Pending";
                    break;

                    case "1":
                        return "Stop";
                    break;
                
                    default:
                        return "Complete";
                    break;
                }
            },

            resetForm() {
                document.getElementById("title").value = "";
                document.getElementById("description").value = "";
                document.getElementById("deadline").value = "";
            },

            taskCreate () {
                const endPoint  = `${this.url}/api/task`;
                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }

               const postData = {
                    title: document.getElementById("title").value,
                    description: document.getElementById("description").value,
                    deadline: document.getElementById("deadline").value,
                    user_id: document.getElementById("userId").value,
                }

                axios.post(endPoint, postData, options)
                .then(response => {
                    this.taskList();
                    this.showMessage(response.data.message, response.data.status);
                    this.resetForm();
                    this.taskForm = false;
                })
                .catch(error => {
                    if (error.response) {
                        const res = error.response.data;
                        this.showMessage(res.message, res.status);
                    } 
                });
            },

            taskUpdate (id) {
                const endPoint  = `${this.url}/api/task/update/${id}`;
                const options = {
                    'headers' : {
                        'Authorization' : `Bearer ${this.token}`
                    }
                }

                const postData = {
                    title: document.getElementById("m-title").value,
                    description: document.getElementById("m-description").value,
                    deadline: document.getElementById("m-deadline").value,
                    status: document.getElementById("m-status").value,
                }

                axios.post(endPoint, postData, options)
                .then(response => {
                    this.taskList();
                    this.showMessage(response.data.message, response.data.status);
                    
                })
                .catch(error => {
                    if (error.response) {
                        const res = error.response.data;
                        this.showMessage(res.message, res.status);
                    } 
                });
            },

            taskDelete (id) {
                if (confirm("Are you sure?")) {
                    const endPoint  = `${this.url}/api/task/${id}`;
                    const options = {
                        'headers': {
                            'Authorization' : `Bearer ${this.token}`
                        }
                    }

                    axios.delete(endPoint, options)
                    .then(response => {
                        this.taskList();
                        this.showMessage(response.data.message, response.data.status);
                    })
                    .catch(error=> {
                        if (error.response) {
                            const res = error.response.data;
                            this.showMessage(res.message, res.status);
                        } 
                    });
                }
            }
        }
    }
</script>


<style scoped>
    textarea::placeholder {
        font-weight: 500; 
        font-size: 14px; 
    }

    textarea {
        resize: none;
        font-size: 15px;
    }

    .pointer {
        cursor: pointer;
    }

    .card-text {
        min-height: 100px;
    }

    .fb{
        font-weight: 500;
    }
</style>