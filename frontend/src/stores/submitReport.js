import axios from 'axios'
import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'
import { Toaster } from 'sonner'
import { useAuthStore } from '@/stores/auth'
import { promiseTimeout } from '@vueuse/core'



export const useSubmitReport = defineStore('submitReport', {
    state: () => {
        return {
            allReports: [],
            reports: [],
            reportDetails: null,
            weeklyCounts: {},
            errors: {},
            isLoading: true
            
            
        }
    },

    getters: {
        pendingCount: (state) => state.allReports.filter(r => r.status === 'pending').length,
        assignedCount: (state) => state.allReports.filter(r => r.status === 'assigned').length,
        inProgressCount: (state) => state.allReports.filter(r => r.status === 'in_progress').length,
        resolvedCount: (state) => state.allReports.filter(r => r.status === 'resolved').length,
    },
    
    actions: {
        async getAllReports(){
            const authStore  = useAuthStore()
            const barangayId = !authStore.isAdmin ? authStore.barangayId : null
            const { data } = await axios.get("/api/reports" , {
                params: {
                    barangayId: barangayId  
                }
            });

            if(data){
                this.allReports = data
                this.errors = {}
                this.isLoading = false;
            }else{  
                this.errors = data.error
            }

            console.log("Fetched reports", this.reports) 
        },

        async getReportById(id){
            console.log(id)
            const { data } = await axios.get(`/api/reports/details/${id}`);
        

            if(data){
                this.reportDetails = data
                this.errors = {}
                this.isLoading = false;
            }else{
                this.errors = data.error
            }
            
            console.log("Fetched reports", this.reportDetails) 
        },

        async submitReport(formData) {
            try {
              const response = await axios.post('/api/reports', formData);
              toast.success(response.data.message || 'Report submitted successfully!');
                this.errors = {}
              return true; 
          
            } catch (error) {
              if (error.response?.status === 422) {

                this.errors = error.response.data.errors
                console.log(this.errors)
                toast.error('Please fix the errors in the form.');
                return error.response.data.errors;
              } else {

                toast.error('An unexpected error occurred. Please try again.');
                return false;
              }
            }
        },
        
    

        async getUserReports(){
            const authStore = useAuthStore()      // access auth store
            const userId = authStore.user?.id   
            const {data} = await axios.get(`/api/reports/user-reports/${userId}`)
            this.allReports = data
            this.reports = data
            console.log(this.reports)
        },


        async getBySearchAndStatusUser(search, status, id){
            const { data } = await axios.get(`/api/reports/user-reports/${id}`, {
                params: {
                    search: search || '',  // optional
                    status: status || 'all' // optional
                }
            });

            this.reports = data;
            return data;
        },

        async getBySearchAndStatusAdmin(search = null, status = null, start = null, end = null){

            const authStore = useAuthStore()
            const barangayId = !authStore.isAdmin ? authStore.user.barangay_id : null
            console.log(search ,status.value ,start, end)
            const { data } = await axios.get(`/api/reports/user-reports/admin`, { 
                params: {
                    search: search || '',
                    status: status.value || 'all',
                    start: start,
                    end: end,
                    barangay_id: barangayId
                }
            });
        
            // make sure it's an array
            this.allReports = Array.isArray(data) ? data : [];
            return this.reports;
        },
        
        async addNewTime(formData){
            console.log(formData)
            const { data } = await axios.post(`/api/report-timelines`, formData);

            if(data){
                this.errors = {}
                this.isLoading = false;
                toast.success(data.message)
            }else{
                this.errors = data.error
            }

            console.log(data)
        },

        async updateReportStatus(id, status, curretUserId){
            console.log(status)
            console.log(curretUserId)
            const { data } = await axios.put(`/api/reports/${id}`, {
                status: status,
                user_id: curretUserId
              })

            if(data){
                this.errors = {}    
                this.isLoading = false;
                toast.success(data.message)
            }else{
                this.errors = data.error
            }

            console.log(data)
        },

        async getDashboardStats(id = null){
            const authStore = useAuthStore()      // access auth store
            const barangayId = !authStore.isAdmin ? authStore.barangayId : null
            if(authStore.userRole === 'LGU_ADMIN'){

                const {data} = await axios.get(`/api/all-report-statuses`)
                this.weeklyCounts = data
            }else {
                const {data} = await axios.get(`/api/report-statuses/${barangayId}`)
                this.weeklyCounts = data
            }
        
        },


        async escalateToLGU(id){
            const authStore  = useAuthStore()
            const curretUserId = authStore.user.id
            const { data } = await axios.put(`/api/barangay/reports/${id}`, {
                report_id: id,
                user_id: curretUserId
            })
            console.log(data)
            if(data){
                this.errors = {}
                this.isLoading = false;
                this.router.push({name: 'barangay.reports-management'})
                console.log(data)
                toast.success(data.message)
            }else{
                this.errors = data.error
                toast.error(data.message)
            }

            
        }



        



    }   
})
