import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from './components/LoginPage.vue';
import DashboardPage from './components/DashboardPage.vue';

const routes = [
    { path: '/', name: 'login', component: LoginPage },
    { path: '/dashboard', name: 'dashboard', component: DashboardPage }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
