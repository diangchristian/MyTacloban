import DashboardView from '@/views/Barangay/DashboardView.vue'
import AnnouncementsView from '@/views/Barangay/AnnouncementsView.vue'
import UserManagementView from '@/views/Barangay/UserManagementView.vue'
import ReportsManagementView from '@/views/Barangay/ReportsManagementView.vue'
import BarangayInformationsView from '@/views/Barangay/BarangayInformationsView.vue'
import EventsManagementView from '@/views/Barangay/EventsManagementView.vue'
import SystemSettingsView from '@/views/Barangay/SystemSettingsView.vue'
import ReportsDetailView from '@/views/Barangay/ReportsDetailView.vue'
import CreateAnnouncementView from '@/views/Barangay/CreateAnnouncementView.vue'

export default [
    {
        path: '/barangay/dashboard',
        name: 'barangay.dashboard',
        component: DashboardView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Dashboard'}
    },
    {
        path: '/barangay/announcements',
        name: 'barangay.announcements',
        component: AnnouncementsView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Announcements'}
    },
    {
        path: '/barangay/announcements/create',
        name: 'barangay.announcements.create',
        component: CreateAnnouncementView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Create New Announcements'}
    },
    {
        path: '/barangay/announcements/:id/edit',
        name: 'barangay.announcements-edit',
        component: CreateAnnouncementView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Edit Announcement'}
    },
    {
        path: '/barangay/user-management',
        name: 'barangay.user-management',
        component: UserManagementView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'User Management'}
    },
    {
        path: '/barangay/reports-management',
        name: 'barangay.reports-management',
        component: ReportsManagementView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Reports Management'}
    },
    {
        path: '/barangay/reports-management/:id/details',
        name: 'barangay.report.details',
        component: ReportsDetailView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Reports Details'}
    },
    {
        path: '/barangay/barangay-informations',
        name: 'barangay.barangay-informations',
        component: BarangayInformationsView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Barangay Informations'}
    },
    {
        path: '/barangay/events-management',
        name: 'barangay.events-management',
        component: EventsManagementView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'Events Management'}
    },
    {
        path: '/barangay/system-settings',
        name: 'barangay.system-settings',
        component: SystemSettingsView,
        meta: {requiresAuth: true, role: ['BARANGAY_STAFF'], layout: 'sidebar', title: 'System Settings'}
    },
]