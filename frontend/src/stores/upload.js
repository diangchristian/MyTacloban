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
    async uploadImages(files) {
      if (!files) return;
    
      const formData = new FormData();
    
      // Normalize input into an array
      const normalizedFiles = Array.isArray(files)
        ? files
        : files instanceof FileList
        ? Array.from(files)
        : [files]; // single file
    
      normalizedFiles.forEach(file => formData.append('images[]', file));
    
      this.isUploading = true;
      this.uploadError = null;
    
      try {
        const { data } = await axios.post('/api/upload-images', formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });
    
        // Can handle return for single or multiple
        this.uploadedFiles = Array.isArray(data.files) ? data.files : [data.files];
        console.log("Upload Result:",  this.uploadedFiles);
        return data;
      } catch (err) {
        this.uploadError = err;
        console.error(err);
      } finally {
        this.isUploading = false;
      }
    },
    

    clearUploadedFiles(){
      this.uploadedFiles = [];
    },
    
  
  },




})