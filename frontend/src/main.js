import './assets/main.css'
import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import 'flowbite'
import App from './App.vue'
import router from './router'
import { useAuthStore } from './stores/auth'
import AOS from 'aos'; // Import the AOS library
import 'aos/dist/aos.css'; // Import the AOS styles

const app = createApp(App)
const pinia =  createPinia()

pinia.use(({store}) => {
    store.router = markRaw(router) // wrap with markRaw to be reactive
})
// main.js or main.ts

// ... imports

AOS.init({
    // Global settings:
    
    // 🚀 Duration (Default: 400ms)
    // Sets the speed of the animation.
    duration: 600, // All animations will take 1200 milliseconds (1.2 seconds)
  
    // ⏱️ Delay (Default: 0ms)
    // Sets the waiting time before the animation starts.
    delay: 200, // All animations will wait for 200 milliseconds before starting
    
    // Other standard settings:
    offset: 120, 
    once: true,
    easing: 'ease-out',
  });
app.use(pinia)
app.use(router)
const authStore = useAuthStore()
authStore.getUser()
app.mount('#app')
