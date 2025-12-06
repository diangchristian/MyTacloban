import axios from 'axios'
import { defineStore } from 'pinia'
import * as LucideIcons from 'lucide-vue-next';



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
      }
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
        // console.log(this.reportCategories)
        this.loading = false
      },
  
      async getAnnouncementCategories() {
        this.loading = true
        const res = await axios.get("/api/announcement_categories")
        this.announcementCategories = res.data
        this.loading = false
      }
    }
  })
  