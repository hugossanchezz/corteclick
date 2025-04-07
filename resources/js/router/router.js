import { createRouter, createWebHistory } from 'vue-router';

// Importa tus componentes de vista aquí
import LandingView from '../../views/pages/LandingView.vue';
import LocalsView from '../../views/pages/LocalsView.vue';
import NewLocalView from '../../views/pages/NewLocalView.vue';
import NotFoundView from '../../views/pages/NotFoundView.vue';

const routes = [
    { path: '/', name: 'home', component: LandingView },
    { path: '/locals', name: 'locals', component: LocalsView },
    { path: '/new-local', name: 'new-local', component: NewLocalView },

    // Ruta comodín para cualquier ruta no encontrada
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFoundView }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
