import axios from 'axios'
import { defineStore } from 'pinia'



export const useEventStore = defineStore('announcement', {
    state: () => {
        return {
            events: [],
            errors: {},
            isLoading: true
            
            
        }
    },

    actions: {
        async getEvents(){
            const { data } = await axios.get("/api/events");
            console.log(data)
            if(data){
                this.events = data
                this.errors = {}
                
            }else{
                this.errors = data.error
            }

            console.log("Fetched events:", this.events) 
        }
    }
})
