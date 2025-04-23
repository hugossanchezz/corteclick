import { createRouter, createWebHistory } from 'vue-router';

// Principal pages
import LandingView from '@/views/pages/LandingView.vue';
import LocalsView from '@/views/pages/LocalsView.vue';
import NewLocalView from '@/views/pages/NewLocalView.vue';

// Auth
import AuthView from '@/views/auth/AuthView.vue';
import LoginForm from "@/js/components/auth/LoginForm.vue";
import RegisterForm from "@/js/components/auth/RegisterForm.vue";
import Terms from "@/js/components/auth/Terms.vue";
import Privacy from "@/js/components/auth/Privacy.vue";
import Cookies from "@/js/components/auth/Cookies.vue";

// User
import ProfileView from '@/views/user/ProfileView.vue';
import SettingsView from '@/views/user/SettingsView.vue';

// Admin
import DashboardView from '@/views/admin/DashboardView.vue';

// 404 Not Found
import NotFoundPage from '@/views/pages/NotFoundView.vue';

const routes = [
    { path: '/', name: 'Home', component: LandingView },
    { path: '/locals', name: 'Locals', component: LocalsView },
    { path: '/new-local', name: 'NewLocal', component: NewLocalView },

    // Auth
    {
        path: '/auth',
        component: AuthView,
        children: [
            { path: '', name: 'Login', component: LoginForm },
            {
                path: 'register',
                component: AuthView,
                children: [
                    {
                        path: '',
                        name: 'registro',
                        component: RegisterForm,
                    },
                    {
                        path: 'terms',
                        name: 'terminos',
                        component: Terms,
                        children: [
                            {
                                path: 'privacy',
                                name: 'privacidad',
                                component: Privacy,
                            },
                            {
                                path: 'cookies',
                                name: 'cookies',
                                component: Cookies,
                            },
                        ],
                    },
                ],
            },
        ],
    },

    // User
    { path: '/profile', name: 'Profile', component: ProfileView },
    { path: '/settings', name: 'Settings', component: SettingsView },

    // Admin
    { path: '/dashboard', name: 'Dashboard', component: DashboardView },

    // 404 Not Found
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundPage }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
