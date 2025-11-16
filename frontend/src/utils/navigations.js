import { 
  Calendar, 
  Home, 
  Inbox, 
  Search, 
  Settings, 
  LayoutDashboard, 
  Newspaper, 
  MessageSquareWarning, 
  ClipboardClock,
  Landmark,
  UserRoundCog,
  Users


} from "lucide-vue-next"

export default function getNavigations(role){

    const userNav = [
        {
            title: "Dashboard",
            url: "/user/dashboard",
            icon: LayoutDashboard,
          },
          {
            title: "Announcements/Advisories",
            url: "/user/announcements",
            icon: Newspaper,
          },
          {
            title: "Submit Report",
            url: "/user/submit-report",
            icon: MessageSquareWarning,
          },
          {
            title: "Reports Tracking",
            url: "/user/reports-tracking",
            icon: ClipboardClock,
          },
          {
            title: "Barangay Informations",
            url: "/user/barangay-informations",
            icon: Landmark,
          },
          {
            title: "Events and Activities",
            url: "/user/events",
            icon: Calendar,
          },
          {
            title: "Profile Settings",
            url: "/user/profile",
            icon: UserRoundCog,
          }
    ]

    const adminNav = [
        {
            title: "Dashboard",
            url: "/admin/dashboard",
            icon: LayoutDashboard,
          },
          {
            title: "Announcements",
            url: "/admin/announcements",
            icon: Newspaper,
          },
          {
            title: "User Management",
            url: "/admin/user-management",
            icon: Users,
          },
          {
            title: "Reports Management",
            url: "/admin/reports-management",
            icon: ClipboardClock,
          },
          {
            title: "Barangay Informations",
            url: "/admin/barangay-informations",
            icon: Landmark,
          },
          {
            title: "Events and Activities",
            url: "/admin/events-management",
            icon: Calendar,
          },
          {
            title: "System Settings",
            url: "/admin/system-settings",
            icon: Settings,
          }
    ]

        return role === 'admin' ? adminNav : userNav
        
}