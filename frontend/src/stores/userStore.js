import { defineStore } from 'pinia'
import { toast } from 'vue-sonner'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [],
    loading: false,
    error: null
  }),

  getters: {
    // Total number of users
    totalUsers: (state) => state.users.length,

    // Count active users
    activeUsers: (state) => {
      return state.users.filter(u => u.status === 'Active').length
    },

    // Count blocked users
    blockedUsers: (state) => {
      return state.users.filter(u => u.status === 'Blocked').length
    },

    // Count pending users
    inactiveUsers: (state) => {
      return state.users.filter(u => u.status === 'Inactive').length
    },

    // Get user by ID
    getUserById: (state) => {
      return (userId) => state.users.find(u => u.id === userId)
    },

    // Filter users by role
    getUsersByRole: (state) => {
      return (role) => state.users.filter(u => u.role === role)
    },

    // Filter users by status
    getUsersByStatus: (state) => {
      return (status) => state.users.filter(u => u.status === status)
    }
  },

  actions: {
    // Fetch all users from API
    async fetchUsers() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get('/api/users')
        const users = response.data

        // Map users to the format needed for the table
        this.users = users.map(user => ({
          id: user.id,
          name: user.full_name,
          email: user.email,
          role: user.role,
          status: user.status || 'Unknown',
          created_at: user.created_at,
          dateJoined: this.formatDate(user.created_at),
          rawData: user // Keep original data if needed
        }))
      } catch (error) {
        this.error = error.message || 'Failed to fetch users'
        console.error('Error fetching users:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    // Create a new user
    async createUser(userData) {
      this.loading = true
      this.error = null

      try {
        const response = await axios.post('/api/register', userData)
        const newUser = {
          id: response.data.id,
          name: response.data.full_name,
          email: response.data.email,
          role: response.data.role,
          status: response.data.status || 'Pending',
          dateJoined: this.formatDate(response.data.created_at),
          rawData: response.data
        }
        
        this.users.push(newUser)
        return newUser
      } catch (error) {
        this.error = error.message || 'Failed to create user'
        console.error('Error creating user:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    // Update an existing user
    async updateUser(userId, userData) {
      console.log('Store updateUser called with:', userId, userData); // Debug log
      
      if (!userId) {
        throw new Error('User ID is required');
      }

      this.loading = true
      this.error = null

      try {
        const response = await axios.put(`/api/user-profile/${userId}`, userData)
        const index = this.users.findIndex(u => u.id === userId)
        
        if (index !== -1) {
          this.users[index] = {
            id: response.data.id,
            name: response.data.full_name,
            email: response.data.email,
            role: response.data.role,
            status: response.data.status,
            dateJoined: this.formatDate(response.data.created_at),
            rawData: response.data
          }
        }
        
        return this.users[index]
      } catch (error) {
        this.error = error.message || 'Failed to update user'
        console.error('Error updating user:', error)
        console.error('Error response:', error.response?.data)
        throw error
      } finally {
        this.loading = false
      }
    },

    // Delete a user
    async deleteUser(userId) {
      console.log('Store deleteUser called with:', userId); // Debug log
      
      if (!userId) {
        throw new Error('User ID is required');
      }

      this.loading = true
      this.error = null

      try {
        await axios.delete(`/api/user-profile/${userId}`)
        this.users = this.users.filter(u => u.id !== userId)
        toast.success('User deleted successfully'); 
      } catch (error) {
        toast.error = error.message || 'Failed to delete user'
        console.error('Error deleting user:', error)
        console.error('Error response:', error.response?.data) // More detailed error
        throw error
      } finally {
        this.loading = false
      }
    },

    // Update user status
    async updateUserStatus(userId, newStatus) {
      return toast.success.updateUser(userId, { status: newStatus })
    },

    // Block a user
    async blockUser(userId) {
      return this.success.updateUserStatus(userId, 'Blocked')
    },

    // Activate a user
    async activateUser(userId) {
      return console.log(updateUserStatus(userId, 'Active'))
    },

    // Helper function to format dates
    formatDate(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },

    // Clear error
    clearError() {
      this.error = null
    }
  }
})