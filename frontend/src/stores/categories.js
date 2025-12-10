import axios from 'axios'
import { defineStore } from 'pinia'
import * as LucideIcons from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export const useCategoriesStore = defineStore('categories', {
    state: () => ({
      eventCategories: [],
      reportCategories: [],
      announcementCategories: [],
      loading: false,
    }),
  
    getters: {
      getIcon: () => (iconName) => {
        return LucideIcons[iconName] || LucideIcons.HelpCircle
      },
      eventCount: (state) => state.eventCategories.length,
      announcementCount: (state) => state.announcementCategories.length,
      reportsCount: (state) => state.reportCategories.length,
    },
  
    actions: {
      async getEventCategories() {
        this.loading = true
        const res = await axios.get("/api/event_categories")
        this.eventCategories = res.data
        this.loading = false
      },
  
      async getReportCategories() {
        this.loading = true
        const res = await axios.get("/api/report_categories")
        this.reportCategories = res.data
        this.loading = false
      },
  
      async getAnnouncementCategories() {
        this.loading = true
        const res = await axios.get("/api/announcement_categories")
        this.announcementCategories = res.data
        this.loading = false
      },

      async createCategory(formData) {

        const {name, type} = formData
        console.log({name: name}, type)
        const url = `/api/${type}_categories`; // dynamic endpoint
        const res = await axios.post(url, {name: name});
        console.log(res.data.message)
      
        if (res.status >= 200 && res.status < 300) {
          toast.success(res.data.message)
        }

        this.callCategoryByType(type)

      },

      async updateCategory(formData) {

        const {id,name, type} = formData
        console.log({name: name}, type)
        const url = `/api/${type}_categories/${id}`; // dynamic endpoint
   
        try{
          const res = await axios.put(url, {name: name});
          console.log(res.data.message)
          toast.success(res.data.message)
        }catch(error){
          toast.error(res.data.message)
        }
  
        this.callCategoryByType(type)
      },

      async deleteCategory(id, type) {
        const url = `/api/${type}_categories/${id}`; // dynamic endpoint
     
        
        try{
          const res = await axios.delete(url);
          console.log(res.data.message)
          toast.success(res.data.message)
        }catch(error){
          toast.error(res.data.message)
        }
  
        this.callCategoryByType(type)
      },

      callCategoryByType(type){
        if (type === 'event') this.getEventCategories();
        else if (type === 'announcement')this.getAnnouncementCategories();
        else if (type === 'report') this.getReportCategories();
      }
  
    }
  })
  