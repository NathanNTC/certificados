import { createRouter, createWebHistory } from 'vue-router';


import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Eventos from '../components/Eventos.vue';
import NewEvent from '../components/NewEvent.vue';
import Inscriptions from '../components/Inscriptions.vue'; 
import Certificate from '../components/Certificate.vue'; 


const isAuthenticated = () => {
  return !!localStorage.getItem('authToken'); 
};

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
      {
        path: '/eventos/:id',
        name: 'EventoDetalhes',
        component: () => import('../components/EventoDetalhes.vue'),
        beforeEnter: (to, from, next) => {
            if (isAuthenticated()) {
                next(); // Permite o acesso se o usuário estiver logado
            } else {
                next('/'); // Redireciona para o login se o usuário não estiver logado
            }
        },
    },
  {
    path: '/eventos',
    name: 'Eventos',
    component: Eventos, // Página para listar eventos
    // Removido o beforeEnter para não ter autenticação
  },
  {
    path: '/new-event',
    name: 'NewEvent',
    component: NewEvent, // Página para adicionar um novo evento
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next(); // Permite o acesso se o usuário estiver logado
      } else {
        next('/'); // Redireciona para o login se o usuário não estiver logado
      }
    },
  },
  {
    path: '/inscricoes',
    name: 'Inscriptions', // Nova rota para Inscrições
    component: Inscriptions, // Componente de Inscrições
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next(); // Permite o acesso se o usuário estiver logado
      } else {
        next('/'); // Redireciona para o login se o usuário não estiver logado
      }
    },
  },
  {
    path: '/certificados',
    name: 'Certificate', // Nova rota para visualizar certificados
    component: Certificate, // Componente para exibição de certificados
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next(); // Permite o acesso se o usuário estiver logado
      } else {
        next('/'); // Redireciona para o login se o usuário não estiver logado
      }
    },
  },
  {
    path: '/validar-certificado',
    name: 'ValidateCertificate',
    component: () => import('../components/Validate.vue'),
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next(); // Permite o acesso se o usuário estiver logado
      } else {
        next('/'); // Redireciona para o login se o usuário não estiver logado
      }
    },
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
