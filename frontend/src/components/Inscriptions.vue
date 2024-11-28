<template>
  <div class="inscriptions">
    <h1>Minhas Inscrições</h1>
    <div v-if="inscriptions.length > 0">
      <div v-for="inscription in inscriptions" :key="inscription.id" class="inscription-card">
        <!-- Botão para cancelar inscrição -->
        <button @click="cancelarInscricao(inscription.id)" class="btn-cancelar">Cancelar Inscrição</button>

        <!-- Detalhes do evento (aparecem automaticamente ao carregar as inscrições) -->
        <div v-if="inscription.eventDetails">
          <p><strong>Nome:</strong> {{ inscription.eventDetails.nome }}</p>
          <p><strong>Data:</strong> {{ formatDate(inscription.eventDetails.data) }}</p>
          <p><strong>Local:</strong> {{ inscription.eventDetails.local }}</p>
        </div>
      </div>
    </div>
    <p v-else>Você não está inscrito em nenhum evento.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const inscriptions = ref([]);
const email = ref(localStorage.getItem('email') || null);
const INSCRIPTION_API_URL = import.meta.env.VITE_API_INSCRIPTION_URL;
const EVENT_API_URL = import.meta.env.VITE_API_EVENT_URL;
const EMAIL_API_URL = import.meta.env.VITE_API_EMAIL_URL;

if (!email.value) {
  console.warn('Nenhum email encontrado no localStorage.');
}

// Formatar data
const formatDate = (date) => {
  const eventDate = new Date(date);
  return `${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}`;
};

// Buscar as inscrições e os detalhes dos eventos ao carregar o componente
onMounted(async () => {
  if (email.value) {
    try {
      const response = await axios.get(`${INSCRIPTION_API_URL}/inscriptions`, {
        headers: { 'User-Email': email.value },
      });
      inscriptions.value = response.data;

      for (const inscription of inscriptions.value) {
        await getEventDetails(inscription.event_id);
      }
    } catch (error) {
      console.error('Erro ao buscar inscrições ou detalhes dos eventos:', error);
    }
  }
});

// Função para buscar os detalhes do evento
const getEventDetails = async (event_id) => {
  try {
    const response = await axios.get(`${EVENT_API_URL}/events/${event_id}`);
    const eventDetails = response.data;
    const inscription = inscriptions.value.find(insc => insc.event_id === event_id);
    if (inscription) {
      inscription.eventDetails = eventDetails;
    }
  } catch (error) {
    console.error('Erro ao buscar detalhes do evento:', error);
  }
};

// Função para enviar o e-mail de cancelamento
const enviarEmailCancelamento = async () => {
  try {
    // Verifica se o e-mail está preenchido antes de enviar
    if (!email?.value) {
      throw new Error('O campo de e-mail está vazio. Por favor, insira um e-mail válido.');
    }

    // Obtém a URL da API a partir da variável de ambiente
    const apiUrl = `${import.meta.env.VITE_API_EMAIL_URL}/send-cancel-email`;

    // Envio do POST para a API
    const response = await axios.post(apiUrl, {
      email: email.value,
    });

    console.log('E-mail de cancelamento enviado com sucesso!', response.data);
  } catch (error) {
    console.error('Erro ao enviar o e-mail de cancelamento:', error);
  }
};

// Cancelar inscrição
const cancelarInscricao = async (inscriptionId) => {
  if (!email.value) {
    alert('Não foi possível encontrar o email do usuário.');
    return;
  }

  try {
    await axios.delete(`${INSCRIPTION_API_URL}/inscriptions/${inscriptionId}`, {
      headers: { 'User-Email': email.value },
    });

    inscriptions.value = inscriptions.value.filter(inscription => inscription.id !== inscriptionId);
    alert('Inscrição cancelada com sucesso!');

    await axios.post(`${EMAIL_API_URL}/send-cancel-email`, { email: email.value });
  } catch (error) {
    console.error('Erro ao cancelar inscrição:', error);
    alert('Erro ao cancelar inscrição.');
  }
};
</script>

<style src="../css/Inscriptions.css"></style>
