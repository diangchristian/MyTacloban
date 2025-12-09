import { createRouter, createWebHistory } from 'vue-router'

import DashboardView from '@/views/User/DashboardView.vue'
import AnnoucementView from '@/views/User/AnnouncementView.vue'

import { useAuthStore } from '../stores/auth'
import UnauthorizedView from '@/views/Error/UnauthorizedView.vue'
import NotFoundView from '@/views/Error/NotFoundView.vue'
import userRoutes from "./user.js"
import publicRoutes from "./public.js"
import adminRoutes from "./admin.js"
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

  // If token exists but user not loaded yet
  if (!authStore.user && localStorage.getItem('token')) {
    try {
      await authStore.getUser(); // ensures user is loaded
    } catch {
      // optional: clear token if getUser fails
      localStorage.removeItem('token');
    }
  }

  const isAuthenticated = authStore.isAuthenticated;
  const userRole = authStore.userRole;

  // Guest-only pages (login/register)
  if (to.meta.guest && isAuthenticated) {
    return next({ name: userRole === 'admin' ? 'admin.dashboard' : 'user.dashboard' });
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