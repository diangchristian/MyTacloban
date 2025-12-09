import axios from 'axios'
import { defineStore } from 'pinia'

export const useBarangayStore = defineStore('barangay', {
  state: () => ({
    barangays: [],
    errors: {},
    isLoading: false
  }),

  actions: {

    async getAllBarangay() {
      this.isLoading = true
      this.errors = {}

      try {
        const { data } = await axios.get("/api/barangays")
        this.barangays = data
        console.log( this.barangays)
      } catch (error) {
        this.errors = error.response?.data || { message: "Error fetching barangays" }
      } finally {
        this.isLoading = false
      }
    },

    async getByName(search) {
      this.isLoading = true
      this.errors = {}

      try {
        const { data } = await axios.get(`/api/search/barangays`, {
          params: { search: search || '' }
        })

        this.barangays = data
      } catch (error) {
        this.errors = error.response?.data || { message: "Error searching barangays" }
      } finally {
        this.isLoading = false
      }
    }

  }
})
