import axios from 'axios'
import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'
import { Toaster } from 'sonner'



export const useSubmitReport = defineStore('submitReport', {
    state: () => {
        return {
            reports: [],
            reportDetails: null,
            errors: {},
            isLoading: true
            
            
        }
    },

    getters: {
        pendingCount: (state) => state.reports.filter(r => r.status === 'pending').length,
        inProgressCount: (state) => state.reports.filter(r => r.status === 'in_progress').length,
        resolvedCount: (state) => state.reports.filter(r => r.status === 'resolved').length,
    },
    


    actions: {
        async getAllReports(){
            const { data } = await axios.get("/api/reports");

            if(data){
                this.reports = data
                this.errors = {}
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
            const {data} = await axios.get('/api/reports/user-reports/1')
            this.reports = data
            console.log(this.reports)
        },


        async getBySearchAndStatus(search, status){
            const { data } = await axios.get(`/api/reports/user-reports/1`, {
                params: {
                    search: search || '',  // optional
                    status: status || 'all' // optional
                }
            });

            this.reports = data;
            return data;
        }
        
    }
})
