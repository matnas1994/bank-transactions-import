import { createRouter, createWebHistory } from 'vue-router';
import ImportPage from '../views/ImportsPage.vue';
import AppLayout from '@/components/layout/AppLayout.vue';

const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            { path: '', component: ImportPage },
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
