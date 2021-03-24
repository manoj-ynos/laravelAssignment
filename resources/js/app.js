require('./bootstrap');

window.Vue = require('vue');

import { Form, HasError, AlertError } from 'vform'
window.Form = Form;

import iosAlertView from 'vue-ios-alertview';
Vue.use(require('vue-moment'));

Vue.use(iosAlertView);

import UsersComponent from './components/UsersComponent';
import ProfileImageComponent from './components/ProfileImageComponent';
import AdduserComponent from './components/AdduserComponent';

import DashboardComponent from './components/DashboardComponent';



Vue.component('users-component', UsersComponent);
Vue.component('profileimage-component', ProfileImageComponent);
Vue.component('adduser-component', AdduserComponent);

Vue.component('dashboard-component', DashboardComponent);


Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('hh:mm A   |   MMMM D')
    }
});

const app = new Vue({
    el: '#app',
    data() {
        return {
            user: AuthUser
        }
    },
    methods: {
        userCan(permission) {
            if(this.user && this.user.allPermissions.includes(permission)) {
                return true;
            }
            return false;
        },
        MakeUrl(path) {
            return BaseUrl(path);
        }
    }
});
