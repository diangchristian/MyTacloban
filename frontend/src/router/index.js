import { createRouter, createWebHistory } from 'vue-router'

import DashboardView from '@/views/User/DashboardView.vue'
import AnnoucementView from '@/views/User/AnnouncementView.vue'

import { useAuthStore } from '../stores/auth'
import UnauthorizedView from '@/views/Error/UnauthorizedView.vue'
import NotFoundView from '@/views/Error/NotFoundView.vue'
import userRoutes from "./user.js"
import publicRoutes from "./public.js"
import adminRoutes from "./admin.js"
import { useUserStore } from '@/stores/userStore'

const routes = [
  ...publicRoutes,
  ...userRoutes,
  ...adminRoutes,
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFoundView,
    meta: {layout: 'noLayout'}
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})



router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  const userStore = useUserStore();

  // Wait for initial auth check to complete
  if (authStore.isLoading && localStorage.getItem('token')) {
    await authStore.getUser();
  }

  const isAuthenticated = authStore.isAuthenticated;
  const userRole = authStore.userRole;

    // Check user status
  if (isAuthenticated && authStore.user) {
    const userStatus = authStore.user.status?.toLowerCase();
    
    if (userStatus !== 'active') {
      // Logout and redirect to login with message
      await authStore.logout();
      localStorage.removeItem('token');
      return next({ 
        name: 'login', 
        query: { 
          message: 'account-deactivated',
          reason: userStatus === 'blocked' ? 'blocked' : 'inactive'
        } 
      });
    }
  }

  // Guest-only pages (login/register)
  if (to.meta.guest && isAuthenticated) {
    return next({ name: userRole === 'Admin' ? 'admin.dashboard' : 'user.dashboard' });
  }

  // Protected routes
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'login' });
  }

  // Role-based routes
  if (to.meta.role && !to.meta.role.includes(userRole)) {
    return next({ name: 'unauthorized' });
  }

  next();
});


export default router