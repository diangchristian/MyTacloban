import DashboardView from '@/views/User/DashboardView.vue'
import AnnoucementView from '@/views/User/AnnouncementView.vue'
import SubmitReportView from '@/views/User/SubmitReportView.vue'
import ReportsTrackingView from '@/views/User/ReportsTrackingView.vue'
import BarangayInformationsView from '@/views/User/BarangayInformationsView.vue'
import EventsView from '@/views/User/EventsView.vue'
import ProfileView from '@/views/User/ProfileView.vue'
export default[
    {
        path: '/user/dashboard',
        name: 'user.dashboard',
        component: DashboardView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar'}
    },
    {
        path: '/user/announcements',
        name: 'user.announcements',
        component: AnnoucementView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Announcements'}
    },
    {
        path: '/user/submit-report',
        name: 'user.submit-report',
        component: SubmitReportView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Submit Report'}
    },
    {
        path: '/user/reports-tracking',
        name: 'user.reports-tracking',
        component: ReportsTrackingView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Reports Tracking' }
    },
    {
        path: '/user/barangay-informations',
        name: 'user.barangay-informations',
        component: BarangayInformationsView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Barangay Information'}
    },
    {
        path: '/user/events',
        name: 'user.events',
        component: EventsView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Events'}
    },
    {
        path: '/user/profile',
        name: 'user.profile',
        component: ProfileView,
        meta: {requiresAuth: true, role: ['user'], layout: 'sidebar', title: 'Profile Settings'}
    },
    
    


]