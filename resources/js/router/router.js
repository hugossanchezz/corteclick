import { createRouter, createWebHistory } from 'vue-router';

// 404 Not found
import NotFoundView from '@/views/pages/NotFoundView.vue';

// Principal pages
import LandingView from '@/views/pages/LandingView.vue';
import AppointmentView from '@/views/pages/AppointmentView.vue';
import NewLocalView from '@/views/pages/NewLocalView.vue';

// Auth
import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';

// User
import ProfileView from '@/views/user/ProfileView.vue';
import SettingsView from '@/views/user/SettingsView.vue';

// Admin
import DashboardView from '@/views/admin/DashboardView.vue';

const routes = [
    { path: '/', name: 'home', component: LandingView },
    { path: '/appointment', name: 'appointment', component: AppointmentView },
    { path: '/new-local', name: 'new-local', component: NewLocalView },

    // Auth
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },

    // User
    { path: '/profile', name: 'profile', component: ProfileView },
    { path: '/settings', name: 'settings', component: SettingsView },

    // Admin
    { path: '/dashboard', name: 'dashboard', component: DashboardView },

    // 404 Not found
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFoundView }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
