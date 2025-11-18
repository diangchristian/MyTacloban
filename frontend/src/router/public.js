import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/Auth/LoginView.vue'
import RegisterView from '@/views/Auth/RegisterView.vue'

export default [
    {
        path: '/',
        name: 'home',
        component: HomeView,
        meta: {guest: true, layout: 'default'}
      },
      {
        path: '/login',
        name: 'login',
        component: LoginView,
        meta: {guest: true, layout: 'noLayout'}
      },
      {
        path: '/register',
        name: 'register',
        component: RegisterView,
        meta: {guest: true, layout: 'noLayout'}
      },
]