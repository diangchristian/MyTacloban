import axios from 'axios'
import { defineStore } from 'pinia'
import * as LucideIcons from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export const useCategoriesStore = defineStore('categories', {
    state: () => ({
      eventCategories: [],
      reportCategories: [],
      announcementCategories: [],
      errors:{},
      isLoading: true,
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
        this.isLoading = true;
        try {
          const res = await axios.get("/api/event_categories");
          this.eventCategories = res.data;
        } catch (error) {
          console.error("Error fetching event categories:", error);
        } finally {
          this.isLoading = false;
        }
      },
      
      async getReportCategories() {
        try {
          const res = await axios.get("/api/report_categories");
          this.reportCategories = res.data;
        } catch (error) {
          console.error("Error fetching report categories:", error);
        } finally {
          this.isLoading = false;
        }
      },
      
      async getAnnouncementCategories() {
        this.isLoading = true;
        try {
          const res = await axios.get("/api/announcement_categories");
          this.announcementCategories = res.data;
        } catch (error) {
          console.error("Error fetching announcement categories:", error);
        } finally {
          this.isLoading = false;
        }
      },
      
      async createCategory(formData) {
      
        const { name, type, slug, icon_name, color } = formData;
        const payload = {
            name: name,
            type: type,
        };
    
        if (type === 'report') {
            payload.slug = slug;
            payload.icon_name = icon_name;
            payload.color = color;
        }
    
        const url = `/api/${type}_categories`;
    
        try {
            const res = await axios.post(url, payload);
    
            if (res.status >= 200 && res.status < 300) {
                toast.success(res.data.message);
                this.errors = {}
                
            }
    
            this.callCategoryByType(type);
    
        } catch (error) {
          this.errors = error.response.data.errors
          console.log(this.errors)
          toast.error("Failed to create category");
        }
    },
    
      async updateCategory(formData) {
        const { id, name, type, slug, icon_name, color } = formData;
        const payload = {
            name: name,
            type: type,
        };
    
        if (type === 'report') {
            payload.slug = slug;
            payload.icon_name = icon_name;
            payload.color = color;
        }
        console.log({name: name}, type)
        const url = `/api/${type}_categories/${id}`; // dynamic endpoint
   
        try{
          const res = await axios.put(url, payload);
         
          console.log(res.data.message)
          toast.success(res.data.message)
          this.callCategoryByType(type)
        }catch(error){
          toast.error(error.message)
        }
  

      },

      async deleteCategory(id, type) {
        
        const url = `/api/${type}_categories/${id}`; 
        console.log(url)
        try{
          const res = await axios.delete(url);
          console.log(res.data.message)
          toast.success(res.data.message)
          this.callCategoryByType(type)
        }catch(error){
          toast.error(error)
        }
  

      },

      callCategoryByType(type){
        if (type === 'event') this.getEventCategories();
        else if (type === 'announcement')this.getAnnouncementCategories();
        else if (type === 'report') this.getReportCategories();
      }
  
    }
  })
  