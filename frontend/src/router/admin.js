import DashboardView from '@/views/Admin/DashboardView.vue'
import AnnouncementsView from '@/views/Admin/AnnouncementsView.vue'
import UserManagementView from '@/views/Admin/UserManagementView.vue'
import ReportsManagementView from '@/views/Admin/ReportsManagementView.vue'
import BarangayInformationsView from '@/views/Admin/BarangayInformationsView.vue'
import EventsManagementView from '@/views/Admin/EventsManagementView.vue'
import SystemSettingsView from '@/views/Admin/SystemSettingsView.vue'



export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: DashboardView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/announcements',
        name: 'admin.announcements',
        component: AnnouncementsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/user-management',
        name: 'admin.user-management',
        component: UserManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/reports-management',
        name: 'admin.reports-management',
        component: ReportsManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/barangay-informations',
        name: 'admin.barangay-informations',
        component: BarangayInformationsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/events-management',
        name: 'admin.events-management',
        component: EventsManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
    {
        path: '/admin/system-settings',
        name: 'admin.system-settings',
        component: SystemSettingsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar'}
    },
]