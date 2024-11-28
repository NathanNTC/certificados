<template>
  <div class="eventos-container" v-if="isAuthenticated">
    <h1>Lista de Eventos</h1>
    <ul class="eventos-list">
      <li 
        v-for="event in events" 
        :key="event.id" 
        class="evento-item"
        @click="redirectToEvent(event.id)"
      >
        <div>
          <strong>{{ event.nome }}</strong>
        </div>
        <div>
          <span>{{ formatDate(event.data) }}</span> | 
          <span>{{ event.local }}</span> | 
     
        </div>
      </li>
    </ul>
    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  </div>
  <div v-else>
    <p>Você precisa estar logado para acessar esta página. <router-link to="/login">Faça login aqui.</router-link></p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

// Variáveis reativas para armazenar os eventos e mensagens de erro
const events = ref([]);
const errorMessage = ref(null);
const isAuthenticated = ref(false);  // Verificação de login


// URL da API de eventos usando a variável de ambiente
const EVENT_API_URL = import.meta.env.VITE_API_EVENT_URL;

// Obtém o token do localStorage para verificar se o usuário está logado
const token = localStorage.getItem('token');

// Router para navegação
const router = useRouter();

// Função para verificar se o usuário está autenticado
const checkAuthentication = () => {
  if (token) {
    isAuthenticated.value = true;
  } else {
    isAuthenticated.value = false;
    router.push('/login');  // Redireciona para a página de login
  }
};

// Função para carregar os eventos
onMounted(async () => {
  checkAuthentication();

  if (isAuthenticated.value) {
    try {
      const response = await fetch(`${EVENT_API_URL}/events`, {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });

      if (response.ok) {
        events.value = await response.json();
      } else {
        throw new Error(`Erro ao obter eventos: ${response.statusText}`);
      }
    } catch (error) {
      console.error('Erro ao conectar à API:', error);
      errorMessage.value = 'Não foi possível carregar os eventos. Tente novamente mais tarde.';
    }
  }
});

// Função para formatar a data de maneira mais legível
const formatDate = (date) => {
  const eventDate = new Date(date);
  return `${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}`;
};

// Função para redirecionar para a página de detalhes do evento
const redirectToEvent = (id) => {
  router.push(`/eventos/${id}`);  // Redireciona para a rota do evento
};
</script>

<style src="../css/eventos.css"></style>
