import { categories } from '@vueuse/core/metadata.mjs';
import axios from 'axios'
import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'

export const useAnnouncementStore = defineStore('announcement', {
    state: () => {
        return {
            announcements: [],
            announcementDetails:[],
            categories: [],
            stats: [],
            errors: {},
            isLoading: true,
        }
    },
    actions: {
        async getAnnouncement(){
            const role = 'admin';
            const { data } = role === 'user' ? 
                                await axios.get("/api/announcements")
                            :   await axios.get("/api/admin/announcements")

            if(data){
                this.announcements = data
                this.errors = {}
                this.isLoading = false
            }else{
                this.errors = data.errors
            }
        },

        async getAnnouncementById(id){
            const { data } = await axios.get(`/api/announcement-details/${id}`)

            if(data){
                this.announcementDetails = data
                this.errors = {}
                this.isLoading = false
            }else{
                this.errors = data.errors
            }

        },

        async getCategories(){
            const { data } = await axios.get("/api/announcement_categories");
          
            if(data){
                this.categories = data
                this.errors = {}
                this.isLoading = false
            }else{
                this.errors = data.errors
            }
        },

        async getStats (){
            const { data } = await axios.get("/api/admin/announcements-stats");
          
            if(data){
                this.stats = data
                this.isLoading = false
                this.errors = {}
            }else{
                this.errors = data.errors
            }
        },


        async getBySearch(search = null, category = null, start = null, end = null){

            console.log(search, category, start, end)
            try {
                const {data} = await axios.get("/api/announcements-search", {
                  params: {
                    search: search,
                    category: category,
                    start: start,
                    end: end,
                  },
                });
                this.announcements = data.data;
                this.isLoading = false
              } catch (error) {
                console.error("Error fetching announcements:", error);
              } finally {
                this.isLoading = false;
              }
        },


        async createAnnouncement(formData) {

            try {
              const { data } = await axios.post("/api/announcements", formData);
          
              toast.success(data.message || "Announcement created successfully!");
              this.isLoading = false;
          
              return true;
          
            } catch (error) {
              this.isLoading = false;
          
              if (error.response && error.response.status === 422) {
                // Laravel validation errors
                this.errors = error.response.data.errors || {};
              } else {
                // Other errors
                toast.error(error.response?.data?.message || "Something went wrong.");
              }
          
              console.log("Validation Errors:", this.errors);
              return false;
            }
        },
          
        async updateAnnouncement(formData, id){
            try{
                const { data } = await axios.put(`/api/announcements/${id}`, formData);
                toast.success(data.message || "Announcement created successfully!");
                this.isLoading = false;
            
                return true;
            }catch(error){
                if(error.response && error.response.status === 422){
                    this.errors = error.response.data.errors || {}        
                }else{
                    toast.error(error.response?.data?.message || "Something went wrong.");
                 
                }
                return false;
            }

       
            

        },

        async deleteAnnouncment(id){
            console.log(id)
            const { data } = await axios.delete(`/api/announcements/${id}`);
            if(data){
                this.errors = {}
                this.isLoading = false
                toast.success(data.message)
                return true;
            }else{
                this.errors = data.errors
                return false;
            }
            
        }
    }
})
