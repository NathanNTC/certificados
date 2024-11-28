<template>
  <div class="evento-detalhes">
    <h1>Detalhes do Evento</h1>
    <div v-if="evento">
      <h2>{{ evento.nome }}</h2>
      <p><strong>Data:</strong> {{ formatDate(evento.data) }}</p>
      <p><strong>Local:</strong> {{ evento.local }}</p>
      <p><strong>Código-ID:</strong> {{ `${evento.codigo}-${evento.id}` }}</p>
      <button @click="inscrever" class="btn-inscrever" :disabled="isLoading">
        {{ isLoading ? 'Processando...' : 'Inscrever-se' }}
      </button>
      <p v-if="message" :class="messageClass">{{ message }}</p>
      <!-- Exibir email na tela -->
      <p v-if="email">Email: {{ email }}</p>
    </div>
    <p v-else class="loading">Carregando os detalhes do evento...</p>
    <button @click="voltar" class="btn-voltar">Voltar</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios'; // Importando o axios

// Router
const route = useRoute();
const router = useRouter();

// Variáveis reativas
const evento = ref(null);
const message = ref('');
const messageClass = ref('');
const isLoading = ref(false);
const EVENT_API_URL = import.meta.env.VITE_API_EVENT_URL;
const INSCRIPTION_API_URL = import.meta.env.VITE_API_INSCRIPTION_URL;
const EMAIL_API_URL = import.meta.env.VITE_API_EMAIL_URL;

const email = ref(localStorage.getItem('email') || null); // Usar ref para reatividade

// Verificar e exibir uma mensagem se o email não estiver definido
if (!email.value) {
  console.warn('Nenhum email encontrado no localStorage.');
}

// Formatar data
const formatDate = (date) => {
  const eventDate = new Date(date);
  return `${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}`;
};

// Buscar detalhes do evento
  onMounted(async () => {
    try {
      const response = await fetch(`${EVENT_API_URL}/events/${route.params.id}`, {
        headers: {
          'User-Email': email.value,
        },
      });

      if (response.ok) {
        evento.value = await response.json();
      } else {
        console.error(`Erro ao carregar evento: ${response.statusText}`);
        message.value = 'Erro ao carregar os detalhes do evento.';
        messageClass.value = 'error';
      }
    } catch (error) {
      console.error('Erro ao buscar detalhes do evento:', error);
      message.value = 'Erro ao buscar detalhes do evento.';
      messageClass.value = 'error';
    }
  });
// Inscrever-se no evento
const inscrever = async () => {
  if (!email.value) {
    message.value = 'Você precisa estar logado para se inscrever.';
    messageClass.value = 'error';
    return;
  }

  isLoading.value = true;
  try {
    const response = await fetch(`${INSCRIPTION_API_URL}/inscriptions`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'User-Email': email.value,
      },
      body: JSON.stringify({
        event_id: route.params.id,
      }),
    });

    if (response.ok) {
      message.value = 'Inscrição realizada com sucesso!';
      messageClass.value = 'success';

      await axios.post(`${EMAIL_API_URL}/send-inscription-email`, {
        email: email.value,
      });
    } else {
      const errorResponse = await response.json();
      console.error('Erro na inscrição!', errorResponse);
      message.value = errorResponse?.message || `Erro: ${response.status} - ${response.statusText}`;
      messageClass.value = 'error';
    }
  } catch (error) {
    console.error('Erro ao inscrever:', error);
    messageClass.value = 'error';
  } finally {
    isLoading.value = false;
  }
};

// Voltar para a lista de eventos
const voltar = () => {
  router.push('/eventos');
};
</script>

<style src="../css/EventoDetalhes.css"></style>
