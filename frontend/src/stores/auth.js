import axios from 'axios'
import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'


export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            errors: {},
            userRole: null,
            isLoading: true
            
            
        }
    },

    getters: {
        userRole: (state) => state.user?.role?.toLowerCase() || null,
        // Checks if the user object is present (logged in)
        isAuthenticated: (state) => !!state.user,
        
        // Convenience getter to check for admin
        isAdmin: (state) => state.user?.role === 'admin'
    },
    actions: {

        async getUser(){
            if(localStorage.getItem('token')){ // Check for token AND if user is not already set
                this.isLoading = true;
                try {
                    const res = await axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`
                        }
                    });
                    
                    // Assuming a successful response from axios is status 2xx
                    this.user = res.data.user;
                    console.log(this.user.id)
                } catch (error) {
                    console.error("Failed to fetch user:", error);
                    // Clear invalid token/state on fetch failure
                    this.logout(false); // Call logout without redirecting immediately
                } finally {
                    this.isLoading = false;
                }
            }
        },



        async Authenticate(apiRoute, formData){
            try {
                console.log('Sending data:', formData);
        
                const response = await axios.post(`/api/${apiRoute}`, formData);
                const data = response.data;
                console.log('Login response:', data);
        
                if(data.errors){
                    this.errors = data.errors;
                } else {
                    // Check user status before allowing login
                    const userStatus = data.user.status?.toLowerCase();
                    
                    if (userStatus !== 'active') {
                        this.errors = { 
                            general: [`Your account is ${userStatus}. Please contact support.`] 
                        };
                        toast.error(`Account ${userStatus}. Please contact support.`);
                        return;
                    }
                    
                    this.user = data.user;
                    this.userRole = data.user.role.toLowerCase()
                    this.errors = {};
                    toast.success(data.message)
                    localStorage.setItem('token', data.token);
        
                    if(data.user.role === 'Admin'){
                        this.router.push({name: "admin.dashboard"});
                    } else {
                        this.router.push({name: "user.dashboard"});
                    }
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors; // show Laravel validation errors
                } else if (error.response && error.response.status === 403) {
                    // Handle account deactivation from backend
                    this.errors = { general: [error.response.data.message] };
                    toast.error(error.response.data.message);
                } else {
                    console.error(error);
                }
            }

            console.log(this.errors)
        },
        

        async updateUserProfile(formData){
            const {data} = await axios.put(`/api/update/user-profile/${this.user.id}`, formData)

            if(data){
                this.user = data.user
                toast.success(data.message || 'Profile updated successfully!');
            }
            console.log(data)
        },


        async  deleteAccount(){
            const data = await axios.delete(`/api/user-profile/${this.user.id}`)
            
            if(data.status === 'succcess'){
                this.router.push({name: "home"})
            }
        },
        
        async logout(redirect = true){ // Added an optional redirect parameter
        
            const res = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            })

            // Data/state cleanup always happens regardless of API success
            this.user = null
            this.errors = {}
            localStorage.removeItem('token')
            
            // Redirect only if explicitly requested or default is true
            if(redirect && this.router) {
                this.router.push({name: "login"})
            }
        }
    }
})