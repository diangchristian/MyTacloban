
import axios from 'axios'
import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'
import {useAuthStore} from "@/stores/auth"





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
            const authStore = useAuthStore()      // access auth store
            const role = authStore.userRole;
            const { data } = role === 'LGU_ADMIN' ? 
                                await axios.get("/api/admin/announcements") :
                               role === 'BARANGAY_STAFF' ?
                                await axios.get(`/api/barangay/announcements/${authStore.user.barangay_id}`)
                                :
                                await axios.get("/api/announcements")

            if(data){
                this.announcements = data
                console.log(this.announcements)
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
            const authStore = useAuthStore()      // access auth store
            const role = authStore.userRole 
            console.log(role)
            const { data } = role === 'LGU_ADMIN' ?
                             await axios.get("/api/admin/announcements-stats") :
                             await axios.get(`/api/barangay/announcements-stats/${authStore.barangayId}`) 
          
            if(data){
                this.stats = data
                this.isLoading = false
                console.log(data)
                this.errors = {}
            }else{
                this.errors = data.errors
            }
        },


        async getBySearch(search = null, category = null, start = null, end = null, status='published'){
            const authStore = useAuthStore();

            const barangayId = !authStore.isAdmin ? authStore.user.barangay_id : null
            console.log(search, category, start, end, status)
            try {
                const {data} = await axios.get("/api/announcements-search", {
                  params: {
                    search: search,
                    category: category,
                    start: start,
                    end: end,
                    status: status,
                    barangayId: barangayId
                  },
                });
                this.announcements = data.data; 
                console.log(data.data)
                this.isLoading = false
              } catch (error) {
                console.error("Error fetching announcements:", error);
              } finally {
                this.isLoading = false;
              }
        },


        async createAnnouncement(formData) {
            console.log(formData)
            try {
              const { data } = await axios.post("/api/announcements", formData);
          
              toast.success(data.message || "Announcement created successfully!");
              this.isLoading = false;
          
              return true;
          
            } catch (error) {
              this.isLoading = false;
                console.log(error   )
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
            const authStore = useAuthStore()
            console.log(id)
            const currentUser = authStore.user.id
            console.log(currentUser)
            const { data } = await axios.delete(`/api/announcements/${id}?user_id=${currentUser}`)
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
