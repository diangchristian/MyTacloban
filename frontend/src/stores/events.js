import axios from "axios";
import { defineStore } from "pinia";
import { toast } from "vue-sonner";
import {useAuthStore} from "@/stores/auth"


export const useEventStore = defineStore("events", {
    state: () => ({
        events: [],
        errors: {},
        isLoading: false,
    }),

    actions: {
        async getEvents() {
            this.isLoading = true;
            const authStore = useAuthStore()    
            const barangayId = !authStore.isAdmin ? authStore.barangayId : null
            console.log(barangayId)
            try {
                const { data } = await axios.get("/api/events", {
                    params: {
                        barangayId: barangayId
                    }
                });
                this.events = data;
                this.errors = {};
            } catch (e) {
                this.errors = e.response?.data ?? { message: "Failed to load events" };
            }

            this.isLoading = false;
        },

        async addEvent(formData) {
            this.isLoading = true;
            const authStore = useAuthStore()    
            const barangayId = !authStore.isAdmin ? authStore.barangayId : null
            
            formData.append('barangay_id', barangayId )
            formData.append('user_id', authStore.user.id )
    
    
            for (const [key, value] of formData.entries()) {
                console.log(key, value)
              }
              
            
            console.log(formData)

            try {
                await axios.post("/api/events", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                await this.getEvents();
                toast.success("Event created successfully!");
                this.errors = {};
            } catch (e) {
                const message = e.response?.data?.message || "Failed to create event";
                toast.error(message);
                this.errors = e.response?.data?.errors ?? { message };
            }

            this.isLoading = false;
        },

        async updateEvent(id, formData) {
            this.isLoading = true;

            try {
                console.log(`Updating event ${id}...`)
                const response = await axios.post(`/api/events/${id}?_method=PUT`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                console.log('Update response:', response.data)

                await this.getEvents();
                toast.success("Event updated successfully!");
                this.errors = {};
            } catch (e) {
                console.error('Update error:', e.response?.data || e)
                const message = e.response?.data?.message || "Failed to update event";
                toast.error(message);
                this.errors = e.response?.data?.errors ?? { message };
            }

            this.isLoading = false;
        },

        async deleteEvent(id) {
            this.isLoading = true;
            const authStore = useAuthStore()    
            const previous = [...this.events];
            this.events = this.events.filter(e => e.id !== id);
            try {
                await axios.delete(`/api/events/${id}`, {
                    params: {
                      user_id: authStore.user.id
                    }
                  });
                  
                await this.getEvents();
                toast.success("Event deleted successfully!");
                this.errors = {};
            } catch (e) {
                this.events = previous; // rollback on failure
                const message = e.response?.data?.message || "Failed to delete event";
                toast.error(message);
                this.errors = e.response?.data ?? { message };
            }

            this.isLoading = false;
        },
    },
});
