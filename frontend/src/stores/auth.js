import axios from 'axios'
import { defineStore } from 'pinia'



export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            errors: {},
            isLoading: true
            
            
        }
    },

    getters: {
        // Checks if the user object is present (logged in)
        isAuthenticated: (state) => !!state.user,
        
        // Returns the user's role string or null if not logged in
        userRole: (state) => state.user ? state.user.role : null,

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
                    console.log(res.data.user)
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
            console.log('clicking authenticate')
 
            const loginReq = await axios.post(`/api/${apiRoute}`, formData)
            const data = await loginReq.data
            console.log(data)
            if(data.errors){
                this.errors = data.errors

            }else{
                this.user = data.user
                this.errors = {}
                localStorage.setItem('token', loginReq.data.token)
                console.log('Login response:', loginReq.data)

                if(data.user.role === 'admin'){
                    this.router.push({name: "AdminPanel"})
                }else{

                    this.router.push({name: "dashboard"})

                }
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