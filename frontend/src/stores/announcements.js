import axios from 'axios'
import { defineStore } from 'pinia'



export const useAnnouncementStore = defineStore('announcement', {
    state: () => {
        return {
            announcement: [],
            errors: {},
            isLoading: true
            
            
        }
    },

    actions: {
        async getAnnouncement(){
            const { data } = await axios.get("/api/announcements");

            if(data){
                this.announcement = data
                this.errors = {}
            }else{
                this.errors = data.error
            }

            console.log("Fetched news:", this.announcement) 
        }
    }
})
