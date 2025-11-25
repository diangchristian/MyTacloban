import DashboardView from '@/views/Admin/DashboardView.vue'
import AnnouncementsView from '@/views/Admin/AnnouncementsView.vue'
import UserManagementView from '@/views/Admin/UserManagementView.vue'
import ReportsManagementView from '@/views/Admin/ReportsManagementView.vue'
import BarangayInformationsView from '@/views/Admin/BarangayInformationsView.vue'
import EventsManagementView from '@/views/Admin/EventsManagementView.vue'
import SystemSettingsView from '@/views/Admin/SystemSettingsView.vue'
import ReportsDetailView from '@/views/Admin/ReportsDetailView.vue'


export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: DashboardView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Dashboard'}
    },
    {
        path: '/admin/announcements',
        name: 'admin.announcements',
        component: AnnouncementsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Announcements'}
    },
    {
        path: '/admin/user-management',
        name: 'admin.user-management',
        component: UserManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'User Management'}
    },
    {
        path: '/admin/reports-management',
        name: 'admin.reports-management',
        component: ReportsManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Reports Management'}
    },
    {
        path: '/admin/reports-management/:id/details',
        name: 'admin.report.details',
        component: ReportsDetailView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Reports Details'}
    },
    {
        path: '/admin/barangay-informations',
        name: 'admin.barangay-informations',
        component: BarangayInformationsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Barangay Informations'}
    },
    {
        path: '/admin/events-management',
        name: 'admin.events-management',
        component: EventsManagementView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'Events Management'}
    },
    {
        path: '/admin/system-settings',
        name: 'admin.system-settings',
        component: SystemSettingsView,
        meta: {requiresAuth: true, role: ['admin'], layout: 'sidebar', title: 'System Settings'}
    },
]