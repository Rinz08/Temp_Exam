import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from './components/LoginPage.vue';
import DashboardPage from './components/DashboardPage.vue';


const routes = [
    { path: '/', name: 'login', component: LoginPage },
    { path: '/dashboard', name: 'dashboard', component: DashboardPage },
    { path: '/products', name: 'products', component: ProductList, meta: { requiresAuth: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    // Debug logging for route transitions
    console.log(`Navigating from ${from.path} to ${to.path}`);

    if (to.meta.requiresAuth && !localStorage.getItem('user')) {
        console.log('Authentication required, redirecting to login...');
        next({ name: 'login' });
    } else {
        console.log('Authentication verified or not required, proceeding...');
        next();
    }
});

export default router;
