// stores/uploadStore.js
import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const useUploadStore = defineStore('upload', {

  state: () => {
    return {
      uploadedFiles: [],
      isUploading: false,
      uploadError: {}
    }
  },

  actions: {
   async uploadImages (files){
      console.log(Array.isArray(files))
      if (!files.length) return
  
      const formData = new FormData()
      files.forEach(file => formData.append('images[]', file))
  
      this.isUploading = true
      this.uploadError = null
  
    
      try {
        const { data } = await axios.post('/api/upload-images', formData)
        this.uploadedFiles = data.files
        console.log(data)
        console.log( this.uploadedFiles)
      } catch (err) {
        this.uploadError= err
      } finally {
        this.isUploading = false
      }
    },

    clearUploadedFiles(){
      this.uploadedFiles = [];
    },
    
  
  },




})