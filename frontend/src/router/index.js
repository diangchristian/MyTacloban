import { createRouter, createWebHistory } from 'vue-router'

import DashboardView from '@/views/User/DashboardView.vue'
import AnnoucementView from '@/views/User/AnnouncementView.vue'

import { useAuthStore } from '../stores/auth'
import UnauthorizedView from '@/views/Error/UnauthorizedView.vue'
import userRoutes from "./user.js"
import publicRoutes from "./public.js"
import adminRoutes from "./admin.js"
const routes = [
  ...publicRoutes,
  ...userRoutes,
  ...adminRoutes,
  {
    path: '/unauthorized',
    name: 'unauthorized',
    component: UnauthorizedView,

  },

]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})



// router.beforeEach(async (to, from, next) => {
//   const authStore = useAuthStore()

//   // Ensure user is loaded if token exists
//   if (!authStore.user && localStorage.getItem('token')) {
//     await authStore.getUser()
//   }

//   const isAuthenticated = authStore.isAuthenticated
//   const userRole = authStore.userRole

//   // Guest-only pages (like login/register)
//   if (to.meta.guest && isAuthenticated) {
//     return next({ name: userRole === 'admin' ? 'AdminPanel' : 'dashboard' })
//   }

//   // Protected routes (require auth)
//   if (to.meta.requiresAuth && !isAuthenticated) {
//     return next({ name: 'login' })
//   }

//   //Role-based protection
//   if (to.meta.role && !to.meta.role.includes(userRole)) {
//     return next({ name: 'unauthorized' })
//   }

//   // ✅ Allow access
//   next()
// })




export default router
