// stores/uploadStore.js
import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import { toast } from 'vue-sonner'



export const useSystemSettingsStore = defineStore('system', {

  state: () => {
    return {
      systemData: {},
      isLoading: false,
      errors: {}
    }
  },

  actions: {
    async getSystemData(){

        try{
            const {data} = await axios.get('/api/system-settings')
            this.systemData = data[0]
            console.log(this.systemData)
        }catch(error){
            this.errors = error
            toast.error(data.message)
            console.log(error)
        }finally{
            this.isLoading = false  
        }

        
    },


    async updateSettings(formData){
        try{
            const {data} = await axios.put('/api/system-settings', formData)
            toast.success(data.message)
        }catch(error){
          if (error.response?.status === 422) {
            this.errors = error
            toast.error('Error updating system settings')
            console.log(error)
          }
           
        }finally{
            this.isLoading = false
        }

        console.log(this.errors)
    }
  
  },




})