import './assets/main.css'

import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import 'flowbite'
import App from './App.vue'
import router from './router'
import { useAuthStore } from './stores/auth'



const app = createApp(App)
const pinia =  createPinia()

pinia.use(({store}) => {
    store.router = markRaw(router) // wrap with markRaw to be reactive
})



app.use(pinia)
app.use(router)
const authStore = useAuthStore()
authStore.getUser()
app.mount('#app')
