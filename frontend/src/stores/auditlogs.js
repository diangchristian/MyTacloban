// stores/uploadStore.js
import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const useAuditLogStore = defineStore('auditlogs', {

  state: () => {
    return {
      logs: [],
      isUploading: false,
      uploadError: {}
    }
  },

  actions: {
    async fetchlogs(){
        
        try{

            const {data} = await axios.get('/api/audit-logs')
            
            console.log(data)
            if(data){
                this.logs = data
            }
        }catch(error){
            console.error(error)
        }
    }
    
  
  },




})