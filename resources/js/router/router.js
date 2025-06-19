import { createRouter, createWebHistory } from 'vue-router';

// Principal pages
import LandingView from '@/views/pages/LandingView.vue';
import NewLocalView from '@/views/pages/NewLocalView.vue';
import LocalsView from '@/views/pages/LocalsView.vue';
import Local from '@/js/components/locals/Local.vue';

// Auth
import AuthView from '@/views/auth/AuthView.vue';
import LoginForm from "@/js/components/auth/LoginForm.vue";
import RegisterForm from "@/js/components/auth/RegisterForm.vue";
import Terms from "@/js/components/auth/Terms.vue";
import Privacy from "@/js/components/auth/Privacy.vue";
import Cookies from "@/js/components/auth/Cookies.vue";

// User
import UserView from '@/views/user/UserView.vue';
import Settings from '@/js/components/user/Settings.vue';
import Appointments from '@/js/components/user/Appointments.vue';


// Admin
import Dashboard from '@/js/components/admin/Dashboard.vue';
import LocalRequests from '@/js/components/admin/LocalRequests.vue';

// 404 Not Found
import NotFoundPage from '@/views/pages/NotFoundView.vue';

const routes = [
    { path: '/', name: 'Home', component: LandingView },
    { path: '/locals', name: 'Locals', component: LocalsView },
    { path: '/locals/:id', name: 'Local', component: Local },
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
    {
        path: '/user', name: 'User', component: UserView,
        children: [
            { path: '', name: 'Appointments', component: Appointments },
            { path: '/admin/dashboard', name: 'Dashboard', component: Dashboard },
            { path: '/admin/requests', name: 'Requests', component: LocalRequests },
            { path: 'settings', name: 'Settings', component: Settings },
        ],
    },

    // 404 Not Found
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundPage }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
