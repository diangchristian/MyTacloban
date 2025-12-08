// stores/barangayOfficialStore.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useBarangayOfficialStore = defineStore('barangayOfficial', {
  state: () => ({
    officials: [],
    currentOfficial: null,
    statistics: null,
    missingPositions: [],
    isLoading: false,
    errors: {},
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0
    }
  }),

  getters: {
    // Get officials by position
    getOfficialsByPosition: (state) => (position) => {
      return state.officials.filter(official => official.position === position);
    },

    // Get officials count
    officialsCount: (state) => state.officials.length,

    // Check if a barangay has all required positions
    hasAllRequiredPositions: (state) => (barangayId) => {
      const requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
      const barangayOfficials = state.officials.filter(o => o.barangay_id === barangayId);
      const positions = barangayOfficials.map(o => o.position);
      return requiredPositions.every(pos => positions.includes(pos));
    }
  },

  actions: {
    async getAllOfficials(params = {}) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        // If no per_page is specified, fetch all records
        const requestParams = {
          per_page: 1000, // Default to fetching all records
          ...params
        };

        const response = await axios.get('/api/barangay-officials', { params: requestParams });
        
        // Handle paginated response
        if (response.data.data) {
          this.officials = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total
          };

          // If there are more pages, fetch them all
          if (response.data.last_page > 1) {
            await this.fetchAllPages(requestParams, response.data.last_page);
          }
        } else {
          this.officials = response.data;
        }
      } catch (error) {
        console.error('Error fetching officials:', error);
        this.errors = { general: ['Failed to fetch officials'] };
      } finally {
        this.isLoading = false;
      }
    },

    async fetchAllPages(params, lastPage) {
      try {
        const promises = [];
        for (let page = 2; page <= lastPage; page++) {
          promises.push(
            axios.get('/api/barangay-officials', { 
              params: { ...params, page } 
            })
          );
        }

        const responses = await Promise.all(promises);
        responses.forEach(response => {
          if (response.data.data) {
            this.officials = [...this.officials, ...response.data.data];
          }
        });
      } catch (error) {
        console.error('Error fetching additional pages:', error);
      }
    },

    async getOfficialsByBarangay(barangayId) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.get(`/api/barangay-officials/barangay/${barangayId}`);
        
        // Don't replace all officials, just return the filtered ones
        // The component will handle filtering from the full officials list
        return response.data.officials || response.data;
      } catch (error) {
        console.error('Error fetching officials by barangay:', error);
        this.errors = { general: ['Failed to fetch officials'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async getOfficialsByPosition(position) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.get(`/api/barangay-officials/position/${position}`);
        return response.data;
      } catch (error) {
        console.error('Error fetching officials by position:', error);
        this.errors = { general: ['Failed to fetch officials by position'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async getOfficial(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.get(`/api/barangay-officials/${id}`);
        this.currentOfficial = response.data;
        return response.data;
      } catch (error) {
        console.error('Error fetching official:', error);
        this.errors = { general: ['Failed to fetch official'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async createOfficial(officialData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.post('/api/barangay-officials', officialData);
        this.officials.push(response.data.data);
        this.pagination.total += 1;
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          this.errors = { general: [error.response?.data?.message || 'Failed to create official'] };
        }
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async updateOfficial(id, officialData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.put(`/api/barangay-officials/${id}`, officialData);
        const index = this.officials.findIndex(o => o.id === id);
        if (index !== -1) {
          this.officials[index] = response.data.data;
        }
        if (this.currentOfficial && this.currentOfficial.id === id) {
          this.currentOfficial = response.data.data;
        }
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          this.errors = { general: [error.response?.data?.message || 'Failed to update official'] };
        }
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async deleteOfficial(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        await axios.delete(`/api/barangay-officials/${id}`);
        this.officials = this.officials.filter(o => o.id !== id);
        this.pagination.total -= 1;
        if (this.currentOfficial && this.currentOfficial.id === id) {
          this.currentOfficial = null;
        }
      } catch (error) {
        console.error('Error deleting official:', error);
        this.errors = { general: ['Failed to delete official'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async getStatistics() {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.get('/api/barangay-officials/statistics');
        this.statistics = response.data;
        return response.data;
      } catch (error) {
        console.error('Error fetching statistics:', error);
        this.errors = { general: ['Failed to fetch statistics'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async getMissingPositions() {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await axios.get('/api/barangay-officials/missing-positions');
        this.missingPositions = response.data;
        return response.data;
      } catch (error) {
        console.error('Error fetching missing positions:', error);
        this.errors = { general: ['Failed to fetch missing positions'] };
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async searchOfficials(searchTerm) {
      return this.getAllOfficials({ search: searchTerm });
    },

    async filterByBarangay(barangayId) {
      return this.getAllOfficials({ barangay_id: barangayId });
    },

    async filterByPosition(position) {
      return this.getAllOfficials({ position: position });
    },

    setCurrentOfficial(official) {
      this.currentOfficial = official;
    },

    clearCurrentOfficial() {
      this.currentOfficial = null;
    },

    clearErrors() {
      this.errors = {};
    },

    clearOfficials() {
      this.officials = [];
    }
  }
});