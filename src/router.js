import { createRouter, createWebHistory } from 'vue-router'; // Importe o Vue Router

// Importe os componentes das visualizações
import DashboardDefault from './views/DashboardDefault.vue';
import ProductTypeComponent from './views/ProductTypeComponent.vue';
import ProductComponent from './views/ProductComponent.vue';
import LoginForm from './views/LoginForm.vue';

// Crie as rotas
const routes = [
  { path: '/', component: DashboardDefault },     
  { path: '/product-type', component: ProductTypeComponent },
  { path: '/product', component: ProductComponent },       
  { path: '/login', component: LoginForm }
];

// Crie uma instância do Vue Router
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;