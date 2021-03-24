<template>
<div class="row" >
    <!--div class="col-md-12">
        <div class="card"-->
           <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                <h4>Specialities</h4>
                <p><b>{{specialities}}</b></p>
                </div>
            </div>
            </div>
            <div class="col-md-6 col-lg-3">
            <div class="widget-small info  coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                <h4>Doctors</h4>
                <p><b>{{doctors}}</b></p>
                </div>
            </div>
            </div>

        </div> 

        <!--/div>   
            
    </div-->
</div>
</template>
<style>
.widget-small.primary.coloured-icon {
    background-color: #fff;
    color: #2a2a2a;
}
.widget-small.primary {
    background-color: #009688;
}
.widget-small.info.coloured-icon {
    background-color: #fff;
    color: #2a2a2a;
}
.widget-small.info {
    background-color: #17a2b8;
}
.widget-small.info.coloured-icon .icon {
    background-color: #17a2b8;
    color: #fff;
}
.widget-small {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    border-radius: 4px;
    color: #FFF;
    margin-bottom: 30px;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
.widget-small.primary.coloured-icon .icon {
    background-color: #009688;
    color: #fff;
}
.widget-small .icon {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    min-width: 85px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 4px 0 0 4px;
    font-size: 2.5rem;
}
.widget-small .info {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    padding: 0 15px;
    -ms-flex-item-align: center;
    align-self: center;
}
.widget-small .info h4 {
    text-transform: uppercase;
    margin: 0;
    margin-bottom: 5px;
    font-weight: 400;
    font-size: .9rem;
}
.widget-small .info p {
    margin: 0;
    font-size: 16px;
}
</style>

<script>
export default {
    
    data() {
        return {
            specialities: 0,
            doctors: 0,
          
        }
    },
    mounted() {
      axios.get(this.$parent.MakeUrl('admin/dashboard/specialities')).then((res) => { 
                this.specialities = res.data;
            }).catch((err) => {

            });
       axios.get(this.$parent.MakeUrl('admin/dashboard/doctors')).then((res) => { 
                this.doctors = res.data;
            }).catch((err) => {

            });
    },
    methods: {
       
    }
}
</script>
