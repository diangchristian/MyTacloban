import DashboardView from '@/views/Admin/DashboardView.vue'
import AnnouncementsView from '@/views/Admin/AnnouncementsView.vue'
import UserManagementView from '@/views/Admin/UserManagementView.vue'
import ReportsManagementView from '@/views/Admin/ReportsManagementView.vue'
import BarangayInformationsView from '@/views/Admin/BarangayInformationsView.vue'
import EventsManagementView from '@/views/Admin/EventsManagementView.vue'
import SystemSettingsView from '@/views/Admin/SystemSettingsView.vue'
import ReportsDetailView from '@/views/Admin/ReportsDetailView.vue'
import CreateAnnouncementView from '@/views/Admin/CreateAnnouncementView.vue'
import AuditLogsView from '@/views/Admin/AuditLogsView.vue'


export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: DashboardView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Dashboard'}
    },
    {
        path: '/admin/announcements',
        name: 'admin.announcements',
        component: AnnouncementsView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Announcements'}
    },
    {
        path: '/admin/announcements/create',
        name: 'admin.announcements.create',
        component: CreateAnnouncementView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Create New Announcements'}
    },
    {
        path: '/admin/announcements/:id/edit',
        name: 'admin.announcements-edit',
        component: CreateAnnouncementView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Edit Announcement'}
    },
    {
        path: '/admin/user-management',
        name: 'admin.user-management',
        component: UserManagementView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'User Management'}
    },
    {
        path: '/admin/reports-management',
        name: 'admin.reports-management',
        component: ReportsManagementView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Reports Management'}
    },
    {
        path: '/admin/reports-management/:id/details',
        name: 'admin.report.details',
        component: ReportsDetailView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Reports Details'}
    },
    {
        path: '/admin/barangay-informations',
        name: 'admin.barangay-informations',
        component: BarangayInformationsView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Barangay Informations'}
    },
    {
        path: '/admin/events-management',
        name: 'admin.events-management',
        component: EventsManagementView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Events Management'}
    },
    {
        path: '/admin/system-settings',
        name: 'admin.system-settings',
        component: SystemSettingsView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'System Settings'}
    },
    {
        path: '/admin/audit-logs',
        name: 'admin.audit-logs',
        component: AuditLogsView,
        meta: {requiresAuth: true, role: ['LGU_ADMIN'], layout: 'sidebar', title: 'Audit Logs'}
    },
]