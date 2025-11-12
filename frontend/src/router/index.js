import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/Auth/LoginView.vue'
import RegisterView from '@/views/Auth/RegisterView.vue'
import DashboardView from '@/views/User/DashboardView.vue'
import AdminPanelView from '@/views/Admin/AdminPanelView.vue'
import { useAuthStore } from '../stores/auth'
import UnauthorizedView from '@/views/Error/UnauthorizedView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {guest: true}
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: {guest: true}
    },
    {
      path: '/user/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: {requiresAuth: true, role: ['user']}
    },
    {
      path: '/admin',
      name: 'AdminPanel',
      component: AdminPanelView,
      meta: {requiresAuth: true, role: ['admin']}
    },
    {
      path: '/unauthorized',
      name: 'unauthorized',
      component: UnauthorizedView,
  
    },

  ],
})



router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  // Ensure user is loaded if token exists
  if (!authStore.user && localStorage.getItem('token')) {
    await authStore.getUser()
  }

  const isAuthenticated = authStore.isAuthenticated
  const userRole = authStore.userRole

  // Guest-only pages (like login/register)
  if (to.meta.guest && isAuthenticated) {
    return next({ name: userRole === 'admin' ? 'AdminPanel' : 'dashboard' })
  }

  // Protected routes (require auth)
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'login' })
  }

  //Role-based protection
  if (to.meta.role && !to.meta.role.includes(userRole)) {
    return next({ name: 'unauthorized' })
  }

  // ✅ Allow access
  next()
})




export default router
