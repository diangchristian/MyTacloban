# Developer Guide

## Clone the repo

```bash
https://github.com/diangchristian/MyTacloban.git
```

## Project Directory
```bash
MyTacloban/
 ├─ frontend/     → Vue 3 Application (Vite)
 ├─ backend/      → Laravel API
 ├─ README.md
```

## Frontend Setup
Navigate to the frontend directory
```bash
cd frontend // vue + vite
```
Install dependecies
```bash
npm install
```
Run the frontend development server
```bash
npm run dev // this will be accessible in http://localhost:3000/
```

## Frontend Directory Guide

This guide explains the purpose of each folder inside the src/ directory.

```bash
src/
 ├─ assets/
 ├─ components/
 ├─ lib/
 ├─ router/
 ├─ stores/
 ├─ utils/
 ├─ views/
 ├─ App.vue
 ├─ main.js

```

### assets/
>Contains static front-end assets such as images, icons, fonts, and global styles.
```bash
Example:
assets/images/logo.png
assets/styles/global.css
main.css
```

### components/
>Reusable Vue components that can be used across multiple pages.
```bash
router/
 ├─ landing/     //components for landing page
 ├─ ui/          // components from shadcn-vue
 ├─ user/        // component for user page
```

### router/
>Reusable Vue components that can be used across multiple pages.
```bash
router/
 ├─ admin.js/       // routes for admin e.g, /admin/dashboard
 ├─ user.js/        // routes for usere.g, /user/dashboard
 ├─ public.js/      // public accessible routes e.g, /login, /register, /
 ├─ index.js/       // main file for combining the different routes 
```

### stores/
>Utility/helper functions that are reused throughout the project.
```bash
utils/
 ├─ navigation.js/       // returns the navigation links depending on the user role
```
### utils/
>Pinia store modules for global app state.
```bash
stores/
 ├─ auth.js/       // user authentication state
```

### views/
>Contains the actual page-level components grouped by feature or user role.
```bash
 views/
 ├─ Admin/     // pages for admin
 ├─ Auth/      // pages for authentication e.g, LoginView or RegisterView
 ├─ Error/     // error pages
 └─ User/      // pages for users
```
> inside the _______View.vue, you can import the components from the **components/** directory to build the page. import the components like:
```js
import DashboardCard from "@/components/user/DashboardCard.vue";
```


## Creating a Branch
>name the branch like feature/user-events-page
```git
git pull    // pull the latest update from the repo
git branch -b branch_name
git checkout branch_name // you are now in the branch
```
> add and commit the branch
```git
git add . 
git commit -m 'message'
git push origin branch_name // this will now be push to its branch
```

### Creating  a Pull Request
- Go to the Github Repo
- Navigate to **Pull Request**
- Click to **New Pull Request**
- Select:
  - base branch: main
  - compare branch: your feature branch
- Fill in the template
