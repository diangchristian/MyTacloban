import axios from "axios";
import { defineStore } from "pinia";
import { toast } from "vue-sonner";

export const useEventStore = defineStore("events", {
    state: () => ({
        events: [],
        errors: {},
        isLoading: false,
    }),

    actions: {
        async getEvents() {
            this.isLoading = true;

            try {
                const { data } = await axios.get("/api/events");
                this.events = data;
                this.errors = {};
            } catch (e) {
                this.errors = e.response?.data ?? { message: "Failed to load events" };
            }

            this.isLoading = false;
        },

        async addEvent(formData) {
            this.isLoading = true;

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
                await axios.put(`/api/events/${id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                await this.getEvents();
                toast.success("Event updated successfully!");
                this.errors = {};
            } catch (e) {
                const message = e.response?.data?.message || "Failed to update event";
                toast.error(message);
                this.errors = e.response?.data?.errors ?? { message };
            }

            this.isLoading = false;
        },

        async deleteEvent(id) {
            this.isLoading = true;

            const previous = [...this.events];
            this.events = this.events.filter(e => e.id !== id);

            try {
                await axios.delete(`/api/events/${id}`);
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
