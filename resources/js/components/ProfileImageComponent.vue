<template>
<div class="row">
    <div class="col-12">
        <div class="alert alert-primary" v-if="message">
            {{ message }}
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Upload Image</h4>
            </div>
			<form @submit.prevent="UpdatePhoto">
            <div class="card-body">
                 <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Image</label>
					<div class="col-sm-12 col-md-7">
						<input type="file" @change='uploadPhoto' name="photo" class="form-control" >
					</div>
					<img v-if="user_image != '' && user_image != null" :src="this.getImage(user_image)" width="64" height="64">
                </div> 
				  
                </div>                        
                              
                <div class="form-group">
				<input type="submit" class="btn btn-success" />
			  </div>
			  </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
props: ['users'],
 data() {
        return {   
			form: new Form({
                   id:this.getUserdata('id'),
				   photo: ''
                }),
           	user_image: this.getUserdata('user_image'),		
            errors: [],           
            message: '',
            loading: false,
            file:''
        }
    },
   
    methods: {  
        getUserdata(key) {
            return (this.users != '')?JSON.parse(this.users)[key]:'';
        },  
        getImage(image){
          return this.$parent.MakeUrl("public/upload/users/"+image);
        },
        uploadPhoto(e){
              let file = e.target.files[0];
                let reader = new FileReader();  

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                     this.form.photo = reader.result;
                    }              
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
		UpdatePhoto(){
			let _this = this;
              this.form.put('admin/users/'+this.form.id)
               .then(()=>{
				console.log("Sucses.....")
                  
               })
              .catch((err) => {
                 console.log("Error.....")
            });
            },
        
        resetForm() {
           
            this.u_image= '';
        }
    }
}
</script>
