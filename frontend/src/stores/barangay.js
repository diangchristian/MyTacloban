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
    },

    async createBarangay(formData) {
      this.isLoading = true
      this.errors = {}

      try {
        const { data } = await axios.post('/api/barangays', formData)
        this.barangays.push(data)
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async updateBarangay(id, formData) {
      this.isLoading = true
      this.errors = {}

      try {
        const { data } = await axios.put(`/api/barangays/${id}`, formData)
        const index = this.barangays.findIndex(b => b.id === id)
        if (index !== -1) {
          this.barangays[index] = data
        }
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async deleteBarangay(id) {
      this.isLoading = true
      this.errors = {}

      try {
        await axios.delete(`/api/barangays/${id}`)
        this.barangays = this.barangays.filter(b => b.id !== id)
      } catch (error) {
        this.errors = error.response?.data || { message: "Error deleting barangay" }
        throw error
      } finally {
        this.isLoading = false
      }
    }

  }
})
