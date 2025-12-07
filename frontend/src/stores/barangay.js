import axios from 'axios'
import { defineStore } from 'pinia'



export const useBarangayStore = defineStore('barangay', {
    state: () => {
        return {
            barangays: [],
            errors: {},
            isLoading: true
            
            
        }
    },

    actions: {
        async getAllBarangay(){
            const { data } = await axios.get("/api/barangays");
            if(data){
                this.barangays = data
                this.errors = {}
                
            }else{
                this.errors = data.error
            }

            console.log("Fetched barangays:", this.barangays) 
        },

        async getByName(search){
            console.log(search)
            const { data } = await axios.get(`/api/search/barangays`, {
                params: {
                    search: search || '',  
                }
            });
            console.log(data)
            if(data){
                this.barangays = data
                this.errors = {}
                
            }else{
                this.errors = data.error
            }

            console.log("Fetched barangays using search queryt:", data) 
        }
    }



})
