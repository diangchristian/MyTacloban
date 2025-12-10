import axios from "axios";
import { defineStore } from "pinia";

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
                const { data } = await axios.post("/api/events", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                this.events.push(data);

                this.errors = {};
            } catch (e) {
                this.errors = e.response?.data?.errors ?? { message: "Create failed" };
            }

            this.isLoading = false;
        },

        async updateEvent(id, formData) {
            this.isLoading = true;

            try {
                const { data } = await axios.post(`/api/events/${id}?_method=PUT`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                const idx = this.events.findIndex(e => e.id === id);

                if (idx !== -1) {
                    // ✅ reactive update
                    this.events[idx] = data;
                }

                this.errors = {};
            } catch (e) {
                this.errors = e.response?.data?.errors ?? { message: "Update failed" };
            }

            this.isLoading = false;
        },

        async deleteEvent(id) {
            if (!confirm("Are you sure you want to delete this event?")) return;

            this.isLoading = true;

            try {
                await axios.delete(`/api/events/${id}`);

                // ✅ reactive delete
                this.events = this.events.filter(e => e.id !== id);

                this.errors = {};
            } catch (e) {
                this.errors = e.response?.data ?? { message: "Delete failed" };
            }

            this.isLoading = false;
        },
    },
});
