<template>
<div class="row" v-if="$parent.userCan('manage-users')">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Users <span v-if="total">({{ total }})</span></h4>
                <div class="card-header-action">
                    <a v-if="$parent.userCan('create-users')" v-bind:href="$parent.MakeUrl('admin/users/create')" class="btn btn-primary">Add <i class="fas fa-plus"></i></a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive table-invoice" v-if="!loading">
                    <table class="table table-striped" v-if="users.length">
                        <tbody>
                            <tr>
								<th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
								<th>Signed Up </th>	
                            </tr>
                            <tr v-for="user, index in users">
								<td><img v-if="user.user_image != ''" :src="getImage(user.user_image)" width="30" height="30"></img>
								</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
								<td v-if="user.u_status === 0">
										
									<button  @click="getProfileLink(user.id, index)" class="btn btn-danger">
                                       Deactive
                                    </button>
								</td>									
								<td v-else>
									<button  @click="getProfileLink(user.id, index)" class="btn btn-success">
                                      Active
                                    </button>
									
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!users.length" class="text-center p-3 text-muted">
                        <h5>No Results</h5>
                        <p>Looks like you have not added any users yet!</p>
                    </div>
                </div>
                <div class="text-center p-4 text-muted" v-else>
                    <h5>Loading</h5>
                    <p>Please wait, data is being loaded...</p>
                </div>
            </div>
        </div>
        <div class="text-center" v-if="users.length && total > users.length">
            <button v-bind:disabled="loading" @click="loadUsers" class="btn btn-primary"><span v-if="loading">Loading <i class="fas fa-spinner fa-spin"></i></span><span v-else>Load More</span></button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            total: 0,
            loading: false,
            loadingmore: false,
            query: '',
            url: ''
        }
    },
   mounted() {
        let query = location.search.split('query=')[1];
        if(query !== undefined) {
            this.query = query;
        }
        this.url = BaseUrl('admin/users?q='+this.query);
        this.loadUsers();
    },
    methods: {
	    
        getImage(image){
          return image ? this.$parent.MakeUrl("public/upload/users/"+image):this.$parent.MakeUrl("public/upload/users/1616149472.jpg");
        },
        loadUsers() {
            let _this = this;
            _this.loading = true;
            axios.get(_this.url).then((res) => {
                _this.users = _this.users.concat(res.data.data);
                _this.total = res.data.total;
                _this.loading = false;
                _this.url = res.data.next_page_url;
            }).catch((err) => {
                _this.loading = false;
            });
        },
		 getProfileLink(id,index) {
            let _this = this;
            this.$iosConfirm({
                title: 'Are you sure?',
            }).then(function() {
                axios.delete(_this.$parent.MakeUrl('admin/users/'+id)).then((res) => {
					window.location.reload()
                    
                }).catch(error => {
                    _this.$iosAlert({
                        'title': 'Error',
                        'text': error.response.data.message
                    });
                });
            });
        }
      
    }
}
</script>
